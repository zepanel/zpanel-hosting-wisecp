/**
 * WISECP v3 zPanel Hosting & Reseller Module - Client Area JavaScript
 */
(function() {
  window.toggleHostingPassword = function(elemId, actualPassword) {
    var el = document.getElementById(elemId);
    if (!el) return;
    if (el.innerText === '••••••••••••') {
      el.innerText = actualPassword;
    } else {
      el.innerText = '••••••••••••';
    }
  };
})();
