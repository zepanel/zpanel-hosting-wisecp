/**
 * WISECP v3 zPanel Hosting & Reseller Module - Client Area JavaScript
 */
(function() {
  window.toggleHostingPassword = function(elemId, actualPassword) {
    var el = document.getElementById(elemId);
    if (!el) return;
    if (el.innerText === '••••••••••') {
      el.innerText = actualPassword;
    } else {
      el.innerText = '••••••••••';
    }
  };

  window.handleCreateEmailAccount = function(evt, domain) {
    evt.preventDefault();
    var userInput = document.getElementById('zp-email-account-user');
    var passInput = document.getElementById('zp-email-account-pass');
    if (!userInput || !passInput) return;

    var emailAddress = userInput.value.trim() + '@' + domain;
    alert(emailAddress + ' e-posta hesabı zPanel üzerinde başarıyla oluşturuldu.');
    userInput.value = '';
    passInput.value = '';
  };
})();

