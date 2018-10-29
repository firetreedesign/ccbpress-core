jQuery(document).ready(function($) {
  jQuery(".ccbpress-required-services.button").click(function() {
    jQuery("button#contextual-help-link").trigger("click");
    return false;
  });

  jQuery(".ccbpress-cron-help").click(function() {
    jQuery("button#contextual-help-link").trigger("click");
    jQuery("#tab-link-ccbpress-cron > a").trigger("click");
    return false;
  });

  jQuery("#ccbpress-ccb-service-check-button").click(function() {
    jQuery("#ccbpress-ccb-service-check-button")
      .text(ccbpress_vars.messages.running)
      .attr("disabled", true)
      .addClass("updating-message");
    data = {
      action: "ccbpress_check_services",
      nonce: ccbpress_vars.nonce
    };

    jQuery.post(ajaxurl, data, function(response) {
      jQuery("#ccbpress-ccb-service-check-results").html(response);
      jQuery("#ccbpress-ccb-service-check-button")
        .text(ccbpress_vars.messages.done)
        .removeClass("updating-message")
        .addClass("updated-message");
      setTimeout(function() {
        jQuery("#ccbpress-ccb-service-check-button")
          .text(ccbpress_vars.messages.connection_test_button)
          .removeClass("updated-message")
          .attr("disabled", false);
      }, 3000);
    });
    return false;
  });

  jQuery(document).on("widget-updated widget-added", function() {
    if (
      typeof jQuery("#widgets-right .ccbpress-select select").chosen ===
      "function"
    ) {
      jQuery("#widgets-right .ccbpress-select select").chosen({
        width: "100%",
        disable_search_threshold: 10
      });
    }
  });

  if (
    typeof jQuery("#widgets-right .ccbpress-select select").chosen ===
    "function"
  ) {
    jQuery("#widgets-right .ccbpress-select select").chosen({
      width: "100%",
      disable_search_threshold: 10
    });
  }
});
