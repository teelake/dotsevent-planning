(function () {
  "use strict";

  var form = document.getElementById("checkout-form");
  if (!form) return;
  if (!window.Square) {
    var err = document.getElementById("card-errors");
    if (err) {
      err.textContent = "Payment SDK failed to load. Check your network and ad blockers.";
      err.removeAttribute("hidden");
    }
    return;
  }

  var appId = form.getAttribute("data-app-id");
  var locationId = form.getAttribute("data-location-id");
  if (!appId || !locationId) return;

  var payBtn = document.getElementById("pay-button");
  var errBox = document.getElementById("card-errors");
  var source = document.getElementById("source_id");

  async function start() {
    try {
      var payments = window.Square.payments(appId, locationId);
      var card = await payments.card();
      await card.attach("#card-container");
      form.addEventListener("submit", handleSubmit);
      async function handleSubmit(e) {
        e.preventDefault();
        if (errBox) {
          errBox.setAttribute("hidden", "hidden");
          errBox.textContent = "";
        }
        if (payBtn) {
          payBtn.disabled = true;
        }
        try {
          var result = await card.tokenize();
          if (result.status === "OK" && result.token) {
            if (source) {
              source.value = result.token;
            }
            form.removeEventListener("submit", handleSubmit);
            form.submit();
            return;
          }
          var det = (result.errors && result.errors[0] && result.errors[0].message) || "Card not accepted. Please check your details.";
          throw new Error(det);
        } catch (x) {
          if (errBox) {
            errBox.textContent = x.message || "Payment could not be started.";
            errBox.removeAttribute("hidden");
          }
        } finally {
          if (payBtn) {
            payBtn.disabled = false;
          }
        }
      }
    } catch (e) {
      if (errBox) {
        errBox.textContent = "Could not start card input.";
        errBox.removeAttribute("hidden");
      }
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", start);
  } else {
    start();
  }
})();
