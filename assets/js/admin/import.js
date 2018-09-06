jQuery(document).ready(function($) {
  /**
   * CCBPress Import
   */
  var CCBPress_Import = new function() {
    this.init = function() {
      var self = this;
      jQuery("#ccbpress-manual-import-button").on(
        "click",
        { self: self },
        self.startImport
      );
      if (
        "running" ==
        jQuery("#ccbpress-manual-import-button").attr("data-ccbpress-status")
      ) {
        self.disableButton();
        self.checkProgress(self);
      }
    };

    this.startImport = function(event) {
      var self = event.data.self;
      self.disableButton();
      data = {
        action: "ccbpress_import",
        nonce: ccbpress_vars.nonce
      };
      jQuery.post(ajaxurl, data, function(response) {
        if ("started" === response) {
          self.updateProgress(
            [{ text: ccbpress_vars.messages.process_running }],
            "running"
          );
          self.checkProgress(self);
        } else {
          self.enableButton();
        }
      });
      return false;
    };

    this.updateProgress = function(content, status) {
      var container = jQuery("#ccbpress-import-status");
      var notice = jQuery("<div />");
      var p = jQuery("<p />");

      notice.attr("class", "notice notice-info");

      for (var key in content) {
        if (content.hasOwnProperty(key)) {
          var br = jQuery("<br />");
          if (content[key].hasOwnProperty("element")) {
            var element = jQuery("<" + content[key]["element"] + " />");
            element.text(content[key]["text"]);
            p.append(element);
            p.append(br);
          } else {
            p.text(content[key]["text"]);
            p.append(br);
          }
        }
      }

      notice.append(p);
      container.html("");
      container.append(notice);
    };

    this.progress = function(content) {
      jQuery("#ccbpress-import-status").html(content);
    };

    this.enableButton = function() {
      jQuery("#ccbpress-manual-import-button")
        .text(ccbpress_vars.messages.done)
        .removeClass("updating-message")
        .addClass("updated-message");
      setTimeout(function() {
        jQuery("#ccbpress-manual-import-button")
          .text(ccbpress_vars.messages.manual_import_button)
          .attr("disabled", false)
          .removeClass("updated-message");
      }, 3000);
    };

    this.disableButton = function() {
      jQuery("#ccbpress-manual-import-button")
        .text(ccbpress_vars.messages.running)
        .attr("disabled", true)
        .addClass("updating-message");
    };

    this.checkProgress = function(self) {
      var checkProgressHandle = setInterval(function() {
        data = {
          action: "ccbpress_import_status",
          nonce: ccbpress_vars.nonce
        };

        jQuery.post(ajaxurl, data, function(response) {
          if ("false" === response) {
            clearInterval(checkProgressHandle);
            self.getLastImport();
            self.enableButton();
            self.updateProgress(
              [{ text: ccbpress_vars.messages.done }],
              "done"
            );
            setTimeout(function() {
              self.progress("");
            }, 3000);
            return;
          }
          self.updateProgress(response, "running");
        });
      }, 5000);
    };

    this.getLastImport = function() {
      data = {
        action: "ccbpress_last_import",
        nonce: ccbpress_vars.nonce
      };
      jQuery.post(ajaxurl, data, function(response) {
        jQuery(".ccbpress-last-import")
          .text(response)
          .css("font-weight", "bold");
        setTimeout(function() {
          jQuery(".ccbpress-last-import").css("font-weight", "normal");
        }, 3000);
      });
    };
  }();
  CCBPress_Import.init();

  /**
   * CCBPress Import
   */
  var CCBPress_Import_Reset = new function() {
    this.init = function() {
      var self = this;
      jQuery("#ccbpress-reset-import-button").on(
        "click",
        { self: self },
        self.confirm
      );
    };

    this.confirm = function(event) {
      var self = event.data.self;
      if (confirm(ccbpress_vars.messages.reset_import_confirmation) == true) {
        self.startReset();
      }
    };

    this.startReset = function() {
      jQuery("#ccbpress-reset-import-button")
        .text(ccbpress_vars.messages.running)
        .attr("disabled", true)
        .addClass("updating-message");
      data = {
        action: "ccbpress_reset_import",
        nonce: ccbpress_vars.nonce
      };

      jQuery.post(ajaxurl, data, function(response) {
        jQuery(".ccbpress-last-import")
          .text(response)
          .css("font-weight", "bold");
        jQuery("#ccbpress-reset-import-button")
          .text(ccbpress_vars.messages.done)
          .removeClass("updating-message")
          .addClass("updated-message");
        setTimeout(function() {
          jQuery(".ccbpress-last-import").css("font-weight", "normal");
          jQuery("#ccbpress-reset-import-button")
            .text(ccbpress_vars.messages.reset_import_button)
            .attr("disabled", false)
            .removeClass("updated-message");
        }, 3000);
      });
      return false;
    };
  }();
  CCBPress_Import_Reset.init();
});
