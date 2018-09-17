!(function(e, t, n) {
  function a() {
    var e = t.getElementsByTagName("script")[0],
      n = t.createElement("script");
    (n.type = "text/javascript"),
      (n.async = !0),
      (n.src = "https://beacon-v2.helpscout.net"),
      e.parentNode.insertBefore(n, e);
  }
  if (
    ((e.Beacon = n = function(t, n, a) {
      e.Beacon.readyQueue.push({ method: t, options: n, data: a });
    }),
    (n.readyQueue = []),
    "complete" === t.readyState)
  )
    return a();
  e.attachEvent
    ? e.attachEvent("onload", a)
    : e.addEventListener("load", a, !1);
})(window, document, window.Beacon || function() {});
window.Beacon("init", "7d4c1d76-5a72-4d7a-865a-30b2f5eb48b5");

Beacon("prefill", {
  name: ccbpress_core_beacon_vars.customer_name,
  email: ccbpress_core_beacon_vars.customer_email
});
