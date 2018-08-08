<?php
/**
 * CCBPress Background Get
 *
 * @since 1.0.3
 *
 * @package CCBPress Core
 */

/**
 * CCBPress Background Get class
 *
 * @since 1.0.3
 *
 * @extends WP_Background_Process
 */
class CCBPress_Background_Get extends WP_Background_Process {

	/**
	 * Action
	 *
	 * @var string
	 */
	protected $action = 'ccbpress_get';

	/**
	 * The task
	 *
	 * @param mixed $item Queue item to iterate over.
	 *
	 * @return mixed
	 */
	protected function task( $item ) {

		/**
		 * Remove if no task is set
		 */
		if ( ! isset( $item ) || ! is_array( $item ) || ! isset( $item['query_string']['srv'] ) ) {
			return false;
		}

		$defaults = array(
			'cache_lifespan'	=> 0,
			'addon'				=> null,
			'refresh_cache'		=> 0,
		);
		$item = wp_parse_args( $item, $defaults );

		/**
		 * Update our import status
		 */
		if ( isset( $item['query_string']['page'] ) && isset( $item['query_string']['per_page'] ) ) {
			update_option( 'ccbpress_import_in_progress', 'Processing batch ' . $item['query_string']['page'] . ', containing ' . $item['query_string']['per_page'] . ' records from ' . $item['query_string']['srv'] . '...' );
		} else {
			update_option( 'ccbpress_import_in_progress', 'Processing ' . $item['query_string']['srv'] . '...' );
		}

		/**
		 * Check if we have reached the rate limit for this srv.
		 * If true, wait 1 second, then push the job to the end of the queue
		 * and return false to proceed to the next job.
		 */
		$rate_limit_ok = CCBPress()->ccb->rate_limit_ok( $item['query_string']['srv'] );

		if ( false === $rate_limit_ok ) {
			update_option( 'ccbpress_import_in_progress', 'Rate metering ' . $item['query_string']['srv'] . '. Pushing to end of queue...' );
			sleep( 1 );
			CCBPress()->get->push_to_queue( $item )->save()->dispatch();
			return false;
		}

		/**
		 * Retrieve the data from Church Community Builder
		 */
		$response = CCBPress()->ccb->get( $item );

		$srv = strtolower( $item['query_string']['srv'] );
		$response = apply_filters( "ccbpress_background_get_{$srv}", $response, $item );

		/**
		 * Check if we are paging through multiple queries
		 */
		if ( isset( $item['query_string']['page'] ) && isset( $item['query_string']['per_page'] ) ) {
			if ( false !== $response ) {
				(int) $item['query_string']['page']++;
				$item['query_string']['page'] = (string) $item['query_string']['page'];
				return $item;
			}
		}

		/**
		 * Done
		 */
		return false;

	}

	public function save() {
		parent::save();
		$this->data = [];
		return $this;
	}

	/**
	 * Complete
	 */
	protected function complete() {
		do_action( 'ccbpress_background_get_complete' );
		parent::complete();
	}

}
