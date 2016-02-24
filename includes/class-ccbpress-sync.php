<?php
class CCBPress_Sync extends WP_Background_Process {

	/**
	 * @var string
	 */
	protected $action = 'ccbpress_sync';

	/**
	 * @param mixed $item Queue item to iterate over
	 *
	 * @return mixed
	 */
	protected function task( $item ) {

		// Remove if no task is set
		if ( ! isset( $item ) || ! is_array( $item ) || ! isset( $item['srv'] ) ) {
			return false;
		}

		switch( $item['srv'] ) {

			case 'group_profiles':
				return $this->ccb_group_profiles( $item );
				break;

		}

		return false;

	}

	/**
	 * Group Profiles
	 */
	protected function ccb_group_profiles( $item ) {

		update_option('ccbpress_group_sync_in_progress', 'Processing batch ' . $item['args']['page']);

		$results = CCBPress()->ccb->group_profiles( $item['args'] );

		if ( ! $results ) {
			return false;
		}

		$groups_count = 0;

		if ( isset( $results->response->groups['count'] ) ) {
			$groups_count = $results->response->groups['count'];
		}

		$group_profiles_db = new CCBPress_Group_Profiles_DB();

		foreach( $results->response->groups->group as $group ) {

			if ( strlen( $group->image ) > 0 ) {
				update_option('ccbpress_group_sync_in_progress', 'Processing batch ' . $item['args']['page'] . '(Downloading ' . esc_attr( $group->name ) . ' image.)');
				CCBPress()->ccb->cache_image( $group->image, $group['id'], 'group' );
			}

			$db_data = array(
				'group_id' 					=> $group['id'],
				'name'						=> $group->name,
				'description'				=> $group->description,
				'campus_id'					=> $group->campus['id'],
				'main_leader_id'			=> $group->main_leader['id'],
				'main_leader_first_name'	=> $group->main_leader->first_name,
				'main_leader_last_name'		=> $group->main_leader->last_name,
				'main_leader_full_name'		=> $group->main_leader->full_name,
				'main_leader_email'			=> $group->main_leader->email,
				'main_leader_phones'		=> json_encode( $group->main_leader->phones ),
				'group_type_id'				=> $group->group_type['id'],
				'department_id'				=> $group->department['id'],
				'area_id'					=> $group->area['id'],
				'calendar_feed'				=> $group->calendar_feed,
				'registration_forms'		=> json_encode( $group->registration_forms ),
				'current_members'			=> $group->current_members,
				'group_capacity'			=> $group->group_capacity,
				'addresses'					=> json_encode( $group->addresses ),
				'meeting_day_id'			=> $group->meeting_day['id'],
				'meeting_day'				=> $group->meeting_day,
				'meeting_time_id'			=> $group->meeting_time['id'],
				'meeting_time'				=> $group->meeting_time,
				'childcare_provided'		=> ( 'true' == $group->childcare_provided ? 1 : 0 ),
				'interaction_type'			=> $group->interaction_type,
				'membership_type'			=> $group->membership_type,
				'notification'				=> ( 'true' == $group->notification ? 1 : 0 ),
				'user_defined_fields'		=> json_encode( $group->user_defined_fields ),
				'listed'					=> ( 'true' == $group->listed ? 1 : 0 ),
				'public_search_listed'		=> ( 'true' == $group->public_search_listed ? 1 : 0 ),
				'inactive'					=> ( 'true' == $group->inactive ? 1 : 0 ),
				'creator_id'				=> $group->creator['id'],
				'creator'					=> $group->creator,
				'modifier_id'				=> $group->modifier['id'],
				'modifier'					=> $group->modifier,
				'created'					=> date('Y-m-d H:i:s', strtotime( $group->created ) ),
				'modified'					=> date('Y-m-d H:i:s', strtotime( $group->modified ) ),
			);

			$exists = $group_profiles_db->get( $group['id'] );

			if ( $exists ) {
				$group_profiles_db->update( $group['id'], $db_data );
			} else {
				$group_profiles_db->insert( $db_data );
			}

			unset( $exists );

		}

		unset( $group_profiles_db );
		unset( $results );

		if ( $groups_count > 0 && $item['args']['per_page'] > 0 && $groups_count == $item['args']['per_page'] ) {
			$item['args']['page'] = $item['args']['page'] + 1;
			return $item;
		}

		delete_option('ccbpress_group_sync_in_progress');
		update_option('ccbpress_last_group_sync', date('Y-m-d H:i:s', current_time( 'timestamp' ) ));
		return false;

	}

	/**
	 * Complete
	 */
	protected function complete() {
		parent::complete();
	}

}
