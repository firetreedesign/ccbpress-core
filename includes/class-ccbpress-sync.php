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

			case 'event_profiles':
				return $this->ccb_event_profiles( $item );
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
	 * Event Profiles
	 */
	protected function ccb_event_profiles( $item ) {

		update_option('ccbpress_event_sync_in_progress', 'Processing batch ' . $item['args']['page']);

		$results = CCBPress()->ccb->event_profiles( $item['args'] );

		if ( ! $results ) {
			delete_option('ccbpress_event_sync_in_progress');
			update_option('ccbpress_last_event_sync', date('Y-m-d H:i:s', current_time( 'timestamp' ) ));
			return false;
		}

		$events_count = 0;

		if ( isset( $results->response->events['count'] ) ) {
			$events_count = $results->response->events['count'];
		}

		$event_profiles_db = new CCBPress_Event_Profiles_DB();

		foreach( $results->response->events->event as $event ) {

			// Skip the event if it is not listed
			if ( 'false' == $event->public_calendar_listed ) {
				continue;
			}

			if ( strlen( $event->image ) > 0 ) {
				update_option('ccbpress_event_sync_in_progress', 'Processing batch ' . $item['args']['page'] . '(Downloading ' . esc_attr( $event->name ) . ' image.)');
				CCBPress()->ccb->cache_image( $event->image, $event['id'], 'event' );
			}

			$db_data = array(
				'event_id'						=> $event['id'],
				'name'							=> $event->name,
				'description'					=> $event->description,
				'leader_notes'					=> $event->leader_notes,
				'start_datetime'				=> date('Y-m-d H:i:s', strtotime( $event->start_datetime, current_time('timestamp') ) ),
				'start_date'					=> $event->start_date,
				'start_time'					=> $event->start_time,
				'end_datetime'					=> date('Y-m-d H:i:s', strtotime( $event->end_datetime, current_time('timestamp') ) ),
				'end_date'						=> $event->end_date,
				'end_time'						=> $event->end_time,
				'timezone'						=> $event->timezone,
				'recurrence_description'		=> $event->recurrence_description,
				'approval_status_id'			=> $event->approval_status['id'],
				'approval_status'				=> $event->approval_status,
				'exceptions'					=> json_encode( $event->exceptions ),
				'group_id'						=> $event->group['id'],
				'group_name'					=> $event->group,
				'organizer_id'					=> $event->organizer['id'],
				'organizer'						=> $event->organizer,
				'phone_type'					=> $event->phone['type'],
				'phone'							=> $event->phone,
				'location_name'					=> $event->location->name,
				'location_street_address'		=> $event->location->street_address,
				'location_city'					=> $event->location->city,
				'location_state'				=> $event->location->state,
				'location_zip'					=> $event->location->zip,
				'location_line_1'				=> $event->location->line_1,
				'location_line_2'				=> $event->location->line_2,
				'registration_limit'			=> $event->registration->limit,
				'registration_event_type_id'	=> $event->registration->event_type['id'],
				'registration_event_type'		=> $event->registration->event_type,
				'registration_forms'			=> json_encode( $event->registration->forms ),
				'resources'						=> json_encode( $event->resources ),
				'setup_start'					=> date('Y-m-d H:i:s', strtotime( $event->setup->start, current_time('timestamp') ) ),
				'setup_end'						=> date('Y-m-d H:i:s', strtotime( $event->setup->end, current_time('timestamp') ) ),
				'setup_notes'					=> $event->setup->notes,
				'event_grouping_id'				=> $event->event_grouping['id'],
				'event_grouping'				=> $event->event_grouping,
				'creator_id'					=> $event->creator['id'],
				'creator'						=> $event->creator,
				'modifier_id'					=> $event->modifier['id'],
				'modifier'						=> $event->modifier,
				'listed'						=> ( 'true' == $event->listed ? 1 : 0 ),
				'public_calendar_listed'		=> ( 'true' == $event->public_calendar_listed ? 1 : 0 ),
				'created'						=> date('Y-m-d H:i:s', strtotime( $event->created, current_time('timestamp') ) ),
				'modified'						=> date('Y-m-d H:i:s', strtotime( $event->modified, current_time('timestamp') ) ),
			);

			$exists = $event_profiles_db->get( $event['id'] );

			if ( $exists ) {
				$event_profiles_db->update( $event['id'], $db_data );
			} else {
				$event_profiles_db->insert( $db_data );
			}

			unset( $exists );

		}

		unset( $event_profiles_db );
		unset( $results );

		if ( $events_count > 0 && $item['args']['per_page'] > 0 && $events_count == $item['args']['per_page'] ) {
			$item['args']['page'] = $item['args']['page'] + 1;
			return $item;
		}


		delete_option('ccbpress_event_sync_in_progress');
		update_option('ccbpress_last_event_sync', date('Y-m-d H:i:s', current_time( 'timestamp' ) ));
		return false;

	}


	/**
	 * Complete
	 */
	protected function complete() {
		parent::complete();
	}

}
