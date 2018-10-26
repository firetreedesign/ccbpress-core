<div>
	<?php
	/**
	 * Check the widget settings to see if it should be displayed.
	 */
	if ( $template->show_group_image( $group ) ) : ?>
		<div class="ccbpress-group-info-image">
			<img src="<?php echo $group->image; ?>" alt="<?php echo $group->name; ?>" />
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Check the widget settings to see if it should be displayed.
	 */
	if ( $template->show_group_name( $group ) ) : ?>
		<div class="ccbpress-group-info-name">
			<?php echo $group->name; ?>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Check the widget settings to see if it should be displayed.
	 */
	if ( $template->show_group_desc( $group ) ) : ?>
		<div class="ccbpress-group-info-desc">
			<?php echo wpautop( $group->description ); ?>
		</div>
	<?php endif; ?>
	<div class="ccbpress-group-info-details" style="<?php echo $template->detail_styles( $group ); ?>">
		<?php
		/**
		 * Check the widget settings to see if it should be displayed.
		 */
		if ( $template->show_group_leader( $group ) ) : ?>
			<div>
				<div class="ccbpress-group-info-leader-title">
					<?php _e('Group Leader:', 'ccbpress-core'); ?>
				</div>
				<div class="ccbpress-group-info-leader-container">
					<?php if ( isset( $group->main_leader->image ) && '' !== $group->main_leader->image ) : ?>
						<img class="ccbpress-group-info-leader-image" src="<?php esc_attr_e( $group->main_leader->image ); ?>" />
					<?php endif; ?>
					<div class="ccbpress-group-info-leader-name">
						<?php
						/**
						 * Check the widget settings to see if it should be displayed.
						 */
						if ( $template->show_group_leader_email( $group ) ) : ?>
							<?php
							echo $template->email_link( array(
								'individual_id' => (string) $group->main_leader['id'],
								'class' => $template->lightbox_class(),
								'link_text' => $group->main_leader->full_name
							) );
							?>
						<?php else: ?>
							<?php echo $group->main_leader->full_name; ?>
						<?php endif; ?>
					</div>
							</div>
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
							<div class="ccbpress-group-info-leader-phone">
								<?php echo $phone; ?> (<?php echo (string)$phone['type']; ?>)
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php
		/**
		 * Check the widget settings to see if it should be displayed.
		 */
		if ( $template->show_registration_forms( $group ) ) : ?>
			<div>
				<div class="ccbpress-group-info-registration-forms-title">
					<?php _e( 'Registration Form(s):', 'ccbpress-core' ); ?>
				</div>
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
						<div class="ccbpress-group-info-registration-form">
							<a href="<?php echo $registration_form->url; ?>" class="<?php echo $template->form_link_class(''); ?>">
								<?php echo $registration_form->name; ?>
							</a>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
