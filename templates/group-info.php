<div class="ccbpress-core-group-info">
	<?php
	/**
	 * Check the widget settings to see if it should be displayed.
	 */
	if ( $template->show_group_image( $group ) ) : ?>
		<div class="ccbpress-core-group-image"><img src="<?php echo $group->image; ?>" alt="<?php echo $group->name; ?>" /></div>
	<?php endif; ?>
	<?php
	/**
	 * Check the widget settings to see if it should be displayed.
	 */
	if ( $template->show_group_name( $group ) ) : ?>
		<div class="ccbpress-core-group-name"><?php echo $group->name; ?></div>
	<?php endif; ?>
	<?php
	/**
	 * Check the widget settings to see if it should be displayed.
	 */
	if ( $template->show_group_desc( $group ) ) : ?>
		<div class="ccbpress-core-group-description"><?php echo wpautop( $group->description ); ?></div>
	<?php endif; ?>
	<?php
	/**
	 * Check the widget settings to see if it should be displayed.
	 */
	if ( $template->show_group_leader( $group ) ) : ?>
		<div class="ccbpress-core-group-leader">
			<div class="ccbpress-core-group-leader-title"><?php _e('Group Leader:', 'ccbpress-core'); ?></div>
			<div class="ccbpress-core-group-leader-name"><?php echo $group->main_leader->full_name; ?></div>
			<?php
			/**
			 * Check the widget settings to see if it should be displayed.
			 */
			if ( $template->show_group_leader_email( $group ) || $template->show_group_leader_phone( $group ) ) : ?>
				<?php
				/**
				 * Check the widget settings to see if it should be displayed.
				 */
				if ( $template->show_group_leader_email( $group ) ) : ?>
					<div class="ccbpress-core-group-leader-email"><?php echo $template->email_link( array( 'individual_id' => (string) $group->main_leader['id'], 'class' => $template->lightbox_class(), 'link_text' => esc_html( 'Send email', 'ccbpress-core' ) ) ); ?></div>
				<?php endif; ?>
				<?php
				/**
				 * Check the widget settings to see if it should be displayed.
				 */
				if ( $template->show_group_leader_phone( $group ) ) : ?>
					<?php
					/**
					 * Loop through all of the phone numbers.
					 */
					foreach ( $group->main_leader->phones->phone as $phone ) : ?>
						<?php
						/**
						 * Check that the phone number is not blank.
						 */
						if ( strlen( $phone ) > 0 ) : ?>
							<div class="ccbpress-core-group-leader-phone"><?php echo $phone; ?> (<?php echo (string)$phone['type']; ?>)</div>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Check the widget settings to see if it should be displayed.
	 */
	if ( $template->show_registration_forms( $group ) ) : ?>
		<div class="ccbpress-core-group-registration-forms">
			<div class="ccbpress-core-group-registration-forms-title"><?php _e( 'Registration Form(s):', 'ccbpress' ); ?></div>
			<?php
			/**
			 * Loop through each registration form
			 */
			foreach ( $group->registration_forms->form as $registration_form ) : ?>
				<?php
				/**
				 * Only show it if the form is still active
				 */
				if ( $template->is_form_active( $registration_form ) ) : ?>
					<div><a href="<?php echo $registration_form->url; ?>" class="<?php echo $template->lightbox_class(); ?>"><?php echo $registration_form->name; ?></a></div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
