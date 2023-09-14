document.addEventListener(
  "DOMContentLoaded",
  function () {
    if (document.querySelector(".ccbpress-required-services.button")) {
      document
        .querySelector(".ccbpress-required-services.button")
        .addEventListener("click", function () {
          document.querySelector("button#contextual-help-link").click();
          return false;
        });
    }

    if (document.querySelector(".ccbpress-cron-help")) {
      document
        .querySelector(".ccbpress-cron-help")
        .addEventListener("click", function () {
          document.querySelector("button#contextual-help-link").click();
          document.querySelector("#tab-link-ccbpress-cron > a").click();
          return false;
        });
    }

    if (document.querySelector("#ccbpress-ccb-service-check-button")) {
      document
        .querySelector("#ccbpress-ccb-service-check-button")
        .addEventListener("click", function () {
          var button = document.getElementById(
            "ccbpress-ccb-service-check-button"
          );
          var button_text = button.textContent || button.innerText;

          button.innerText = ccbpress_vars.messages.running;
          button.setAttribute("disabled", true);
          button.classList.add("updating-message");

          fetch(
            ccbpress_vars.api_url + "ccbpress/v1/admin/check_api_services",
            {
              method: "POST",
              headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
              },
              body: JSON.stringify({
                _wpnonce: ccbpress_vars.api_nonce,
              }),
            }
          )
            .then((response) => response.json())
            .then((data) => {
              if (Array.isArray(data)) {
                let table = document.createElement("table");
                table.className += "wp-list-table widefat fixed striped";
                table.style.margin = "15px 0 0 0";
                let header = table.createTHead();
                let row = header.insertRow();
                let th = document.createElement("th");
                th.innerText = "API Service";
                th.style.padding = "8px 10px";
                row.appendChild(th);
                th = document.createElement("th");
                th.innerText = "Status";
                th.style.padding = "8px 10px";
                row.appendChild(th);
                data.forEach((item) => {
                  let row = table.insertRow();
                  let cell = row.insertCell();
                  cell.innerText = item.service;
                  cell = row.insertCell();
                  if ("Passed" === item.status) {
                    cell.innerHTML =
                      '<div class="dashicons dashicons-yes"></div> ' +
                      item.status;
                  } else {
                    cell.innerHTML =
                      '<div class="dashicons dashicons-no"></div> ' +
                      item.status;
                  }
                });
                document.querySelector(
                  "#ccbpress-ccb-service-check-results"
                ).innerHTML = "";
                document
                  .querySelector("#ccbpress-ccb-service-check-results")
                  .appendChild(table);
                button.innerText = ccbpress_vars.messages.done;
                button.classList.remove("updating-message");
                button.classList.add("updated-message");

                setTimeout(function () {
                  button.innerText = button_text;
                  button.classList.remove("updated-message");
                  button.removeAttribute("disabled");
                }, 3000);
              }
            });
          return false;
        });
    }

    document.addEventListener("widget-updated widget-added", function () {
      if (
        document.querySelector("#widgets-right .ccbpress-select select") &&
        document.querySelector("#widgets-right .ccbpress-select select").dataset
          .chosen
      ) {
        document
          .querySelector("#widgets-right .ccbpress-select select")
          .chosen({
            width: "100%",
            disable_search_threshold: 10,
          });
      }
    });

    if (
      document.querySelector("#widgets-right .ccbpress-select select") &&
      document.querySelector("#widgets-right .ccbpress-select select").dataset
        .chosen
    ) {
      document.querySelector("#widgets-right .ccbpress-select select").chosen({
        width: "100%",
        disable_search_threshold: 10,
      });
    }

    if (document.querySelector("#ccbpress-purge-image-cache-button")) {
      document
        .querySelector("#ccbpress-purge-image-cache-button")
        .addEventListener("click", function () {
          var button = document.getElementById(
            "ccbpress-purge-image-cache-button"
          );
          var button_text = button.textContent || button.innerText;

          button.innerText = ccbpress_vars.messages.running;
          button.setAttribute("disabled", true);
          button.classList.add("updating-message");

          fetch(ccbpress_vars.api_url + "ccbpress/v1/admin/purge_image_cache", {
            method: "POST",
            headers: {
              Accept: "application/json",
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              _wpnonce: ccbpress_vars.api_nonce,
            }),
          })
            .then((response) => response.json())
            .then((data) => {
              if (
                typeof data.result !== "undefined" &&
                "success" === data.result
              ) {
                button.innerText = ccbpress_vars.messages.done;
                button.classList.remove("updating-message");
                button.classList.add("updated-message");
                setTimeout(function () {
                  button.innerText = button_text;
                  button.classList.remove("updated-message");
                  button.removeAttribute("disabled");
                }, 3000);
              } else {
                button.innerText = button_text;
                button.classList.remove("updated-message");
                button.removeAttribute("disabled");
                alert("There was an error purging the image cache.");
              }
            })
            .catch((err) => {
              button.innerText = button_text;
              button.classList.remove("updated-message");
              button.removeAttribute("disabled");
              alert("There was an error purging the image cache.");
            });
        });
    }

    if (document.querySelector("#ccbpress-purge-transient-cache-button")) {
      document
        .querySelector("#ccbpress-purge-transient-cache-button")
        .addEventListener("click", function () {
          var button = document.getElementById(
            "ccbpress-purge-transient-cache-button"
          );
          var button_text = button.textContent || button.innerText;

          button.innerText = ccbpress_vars.messages.running;
          button.setAttribute("disabled", true);
          button.classList.add("updating-message");

          fetch(
            ccbpress_vars.api_url + "ccbpress/v1/admin/purge_transient_cache",
            {
              method: "POST",
              headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
              },
              body: JSON.stringify({
                _wpnonce: ccbpress_vars.api_nonce,
              }),
            }
          )
            .then((response) => response.json())
            .then((data) => {
              if (
                typeof data.result !== "undefined" &&
                "success" === data.result
              ) {
                button.innerText = ccbpress_vars.messages.done;
                button.classList.remove("updating-message");
                button.classList.add("updated-message");
                setTimeout(function () {
                  button.innerText = button_text;
                  button.classList.remove("updated-message");
                  button.removeAttribute("disabled");
                }, 3000);
              } else {
                button.innerText = button_text;
                button.classList.remove("updated-message");
                button.removeAttribute("disabled");
                alert("There was an error purging the transient cache.");
              }
            })
            .catch((err) => {
              button.innerText = button_text;
              button.classList.remove("updated-message");
              button.removeAttribute("disabled");
              alert("There was an error purging the transient cache.");
            });
        });
    }

    if (document.querySelector("#ccbpress-reschedule-cron-jobs-button")) {
      document
        .querySelector("#ccbpress-reschedule-cron-jobs-button")
        .addEventListener("click", function () {
          var button = document.getElementById(
            "ccbpress-reschedule-cron-jobs-button"
          );
          var button_text = button.textContent || button.innerText;

          button.innerText = ccbpress_vars.messages.running;
          button.setAttribute("disabled", true);
          button.classList.add("updating-message");

          fetch(
            ccbpress_vars.api_url + "ccbpress/v1/admin/reschedule_cron_jobs",
            {
              method: "POST",
              headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
              },
              body: JSON.stringify({
                _wpnonce: ccbpress_vars.api_nonce,
              }),
            }
          )
            .then((response) => response.json())
            .then((data) => {
              if (
                typeof data.result !== "undefined" &&
                "success" === data.result
              ) {
                button.innerText = ccbpress_vars.messages.done;
                button.classList.remove("updating-message");
                button.classList.add("updated-message");
                setTimeout(function () {
                  button.innerText = button_text;
                  button.classList.remove("updated-message");
                  button.removeAttribute("disabled");
                }, 3000);
              } else {
                button.innerText = button_text;
                button.classList.remove("updated-message");
                button.removeAttribute("disabled");
                alert("There was an error rescheduling the cron jobs.");
              }
            })
            .catch((err) => {
              button.innerText = button_text;
              button.classList.remove("updated-message");
              button.removeAttribute("disabled");
              alert("There was an error rescheduling the cron jobs.");
            });
        });
    }
  },
  false
);
