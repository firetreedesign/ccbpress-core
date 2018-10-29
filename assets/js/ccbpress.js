jQuery(function() {
  var options = {
    variant: "ccbpress-iframe",
    loading: "Loading...",
    closeIcon: "<span class='dashicons dashicons-no-alt'></span>"
  };
  if (typeof jQuery(".ccbpress-lightbox").featherlight === "function") {
    jQuery(".ccbpress-lightbox").featherlight("iframe", options);
  }
});
