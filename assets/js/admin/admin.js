jQuery(document).ready(function($) {
	jQuery('.ccbpress-required-services.button').click(function() {
		jQuery('button#contextual-help-link').trigger('click');
		return false;
	});

	jQuery('.ccbpress-cron-help').click(function() {
		jQuery('button#contextual-help-link').trigger('click');
		jQuery('#tab-link-ccbpress-cron > a').trigger('click');
		return false;
	});

	jQuery('#ccbpress-ccb-service-check-button').click(function() {
		var button = document.getElementById('ccbpress-ccb-service-check-button');
		var button_text = button.textContent || button.innerText;

		jQuery('#ccbpress-ccb-service-check-button')
			.text(ccbpress_vars.messages.running)
			.attr('disabled', true)
			.addClass('updating-message');

		fetch(ccbpress_vars.api_url + 'ccbpress/v1/admin/check_api_services', {
			method: 'POST',
			headers: {
				Accept: 'application/json',
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({
				_wpnonce: ccbpress_vars.api_nonce,
			}),
		})
			.then(response => response.json())
			.then(data => {
				if (Array.isArray(data)) {
					let table = document.createElement('table');
					table.className += 'wp-list-table widefat fixed striped';
					table.style.margin = '15px 0 0 0';
					let header = table.createTHead();
					let row = header.insertRow();
					let th = document.createElement('th');
					th.innerText = 'API Service';
					th.style.padding = '8px 10px';
					row.appendChild(th);
					th = document.createElement('th');
					th.innerText = 'Status';
					th.style.padding = '8px 10px';
					row.appendChild(th);
					data.forEach(item => {
						let row = table.insertRow();
						let cell = row.insertCell();
						cell.innerText = item.service;
						cell = row.insertCell();
						if ('Passed' === item.status) {
							cell.innerHTML =
								'<div class="dashicons dashicons-yes"></div> ' + item.status;
						} else {
							cell.innerHTML =
								'<div class="dashicons dashicons-no"></div> ' + item.status;
						}
					});
					jQuery('#ccbpress-ccb-service-check-results').html(table);
					jQuery('#ccbpress-ccb-service-check-button')
						.text(ccbpress_vars.messages.done)
						.removeClass('updating-message')
						.addClass('updated-message');
					setTimeout(function() {
						jQuery('#ccbpress-ccb-service-check-button')
							.text(button_text)
							.removeClass('updated-message')
							.attr('disabled', false);
					}, 3000);
				}
			});
		return false;
	});

	jQuery(document).on('widget-updated widget-added', function() {
		if (
			typeof jQuery('#widgets-right .ccbpress-select select').chosen ===
			'function'
		) {
			jQuery('#widgets-right .ccbpress-select select').chosen({
				width: '100%',
				disable_search_threshold: 10,
			});
		}
	});

	if (
		typeof jQuery('#widgets-right .ccbpress-select select').chosen ===
		'function'
	) {
		jQuery('#widgets-right .ccbpress-select select').chosen({
			width: '100%',
			disable_search_threshold: 10,
		});
	}

	jQuery('#ccbpress-purge-image-cache-button').click(function() {
		var button = document.getElementById('ccbpress-purge-image-cache-button');
		var button_text = button.textContent || button.innerText;

		jQuery('#ccbpress-purge-image-cache-button')
			.text(ccbpress_vars.messages.running)
			.attr('disabled', true)
			.addClass('updating-message');

		fetch(ccbpress_vars.api_url + 'ccbpress/v1/admin/purge_image_cache', {
			method: 'POST',
			headers: {
				Accept: 'application/json',
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({
				_wpnonce: ccbpress_vars.api_nonce,
			}),
		})
			.then(response => response.json())
			.then(data => {
				if (typeof data.result !== 'undefined' && 'success' === data.result) {
					jQuery('#ccbpress-purge-image-cache-button')
						.text(ccbpress_vars.messages.done)
						.removeClass('updating-message')
						.addClass('updated-message');
					setTimeout(function() {
						jQuery('#ccbpress-purge-image-cache-button')
							.text(button_text)
							.removeClass('updated-message')
							.attr('disabled', false);
					}, 3000);
				} else {
					jQuery('#ccbpress-purge-image-cache-button')
						.text(button_text)
						.removeClass('updated-message')
						.attr('disabled', false);
					alert('There was an error purging the image cache.');
				}
			})
			.catch(err => {
				jQuery('#ccbpress-purge-image-cache-button')
					.text(button_text)
					.removeClass('updated-message')
					.attr('disabled', false);
				alert('There was an error purging the image cache.');
			});
	});

	jQuery('#ccbpress-purge-transient-cache-button').click(function() {
		var button = document.getElementById(
			'ccbpress-purge-transient-cache-button',
		);
		var button_text = button.textContent || button.innerText;

		jQuery('#ccbpress-purge-transient-cache-button')
			.text(ccbpress_vars.messages.running)
			.attr('disabled', true)
			.addClass('updating-message');

		fetch(ccbpress_vars.api_url + 'ccbpress/v1/admin/purge_transient_cache', {
			method: 'POST',
			headers: {
				Accept: 'application/json',
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({
				_wpnonce: ccbpress_vars.api_nonce,
			}),
		})
			.then(response => response.json())
			.then(data => {
				if (typeof data.result !== 'undefined' && 'success' === data.result) {
					jQuery('#ccbpress-purge-transient-cache-button')
						.text(ccbpress_vars.messages.done)
						.removeClass('updating-message')
						.addClass('updated-message');
					setTimeout(function() {
						jQuery('#ccbpress-purge-transient-cache-button')
							.text(button_text)
							.removeClass('updated-message')
							.attr('disabled', false);
					}, 3000);
				} else {
					jQuery('#ccbpress-purge-transient-cache-button')
						.text(button_text)
						.removeClass('updated-message')
						.attr('disabled', false);
					alert('There was an error purging the transient cache.');
				}
			})
			.catch(err => {
				jQuery('#ccbpress-purge-transient-cache-button')
					.text(button_text)
					.removeClass('updated-message')
					.attr('disabled', false);
				alert('There was an error purging the transient cache.');
			});
	});
});
