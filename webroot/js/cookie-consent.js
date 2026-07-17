(function () {
  var COOKIE_NAME = "cc_cookie_consent";
  var MAX_AGE_DAYS = 180;

  function getCookie(name) {
    var m = document.cookie.match(
      new RegExp("(^|;\\s*)" + name.replace(/[.*+?^${}()|[\\]\\\\]/g, "\\$&") + "=([^;]*)")
    );
    return m ? decodeURIComponent(m[2]) : null;
  }

  function setCookie(name, value, maxAgeDays) {
    var maxAge = maxAgeDays * 24 * 60 * 60;
    var secure = window.location.protocol === "https:" ? "; Secure" : "";
    document.cookie =
      name +
      "=" +
      encodeURIComponent(value) +
      "; Path=/" +
      "; Max-Age=" +
      String(maxAge) +
      "; SameSite=Lax" +
      secure;
  }

  function showBanner(el) {
    el.classList.remove("hidden");
    el.setAttribute("aria-hidden", "false");
  }

  function hideBanner(el) {
    el.classList.add("hidden");
    el.setAttribute("aria-hidden", "true");
  }

  function init() {
    var banner = document.getElementById("cookie-consent");
    if (!banner) return;

    var existing = getCookie(COOKIE_NAME);
    if (existing === "accept" || existing === "reject") {
      hideBanner(banner);
      return;
    }

    showBanner(banner);

    banner.addEventListener("click", function (e) {
      var t = e.target;
      if (!t || !(t instanceof Element)) return;
      var btn = t.closest("[data-cookie-consent-action]");
      if (!btn) return;

      var action = btn.getAttribute("data-cookie-consent-action");
      if (action !== "accept" && action !== "reject") return;

      setCookie(COOKIE_NAME, action, MAX_AGE_DAYS);
      hideBanner(banner);
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();

