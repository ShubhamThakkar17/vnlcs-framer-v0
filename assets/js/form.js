/* Admission enquiry form.
 * - Client-side validation with clear, field-level errors (Indian mobile format).
 * - Two honeypots (Web3Forms "botcheck" + our "website" field): bots get a fake success.
 * - Sends to Web3Forms once per access key, so both school inboxes receive it on the free plan.
 * - Without JavaScript the form still posts to Web3Forms (first key) and redirects back.
 */
(function () {
  'use strict';
  var form = document.getElementById('enquiry-form');
  if (!form) return;
  var status = form.querySelector('.form-status');
  var thanks = form.parentNode.querySelector('.form-thanks');
  var submit = form.querySelector('[type="submit"]');
  var keys = [];
  try { keys = JSON.parse(form.getAttribute('data-keys') || '[]'); } catch (e) {}

  function normalisePhone(v) {
    var d = String(v || '').replace(/[\s\-().]/g, '');
    d = d.replace(/^\+?91(?=\d{10}$)/, '').replace(/^0(?=\d{10}$)/, '');
    return d;
  }

  function setError(input, msg) {
    var err = document.getElementById('e-' + input.id.replace(/^f-/, ''));
    if (msg) {
      input.setAttribute('aria-invalid', 'true');
      if (err) { err.textContent = msg; err.hidden = false; }
    } else {
      input.removeAttribute('aria-invalid');
      if (err) { err.textContent = ''; err.hidden = true; }
    }
    return !msg;
  }

  function validate() {
    var name = form.elements.name, phone = form.elements.phone, email = form.elements.email;
    var ok = true, first = null;
    function check(input, msg) { if (!setError(input, msg)) { ok = false; first = first || input; } }
    check(name, name.value.trim().length < 2 ? 'Please enter the parent\'s name.' : '');
    var p = normalisePhone(phone.value);
    check(phone, !p ? 'Please enter a mobile number so we can call you back.'
      : !/^[6-9]\d{9}$/.test(p) ? 'Please enter a 10-digit Indian mobile number, for example 98765 43210.' : '');
    var m = email.value.trim();
    check(email, m && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(m) ? 'Please check the email address, for example name@gmail.com.' : '');
    if (first) first.focus();
    return ok;
  }

  ['name', 'phone', 'email'].forEach(function (n) {
    form.elements[n].addEventListener('blur', function () {
      if (form.elements[n].getAttribute('aria-invalid') === 'true') validate();
    });
  });

  function showThanks() {
    form.hidden = true;
    if (thanks) { thanks.hidden = false; thanks.focus(); }
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    status.textContent = '';

    // Bots fill hidden fields: pretend it worked, send nothing.
    if (form.elements.botcheck.checked || form.elements.website.value) { showThanks(); return; }
    if (!validate()) { status.textContent = 'Please fix the highlighted fields.'; return; }

    var data = {};
    new FormData(form).forEach(function (v, k) {
      if (['access_key', 'redirect', 'website', 'botcheck'].indexOf(k) === -1) data[k] = v;
    });
    data.phone = normalisePhone(data.phone);
    data.botcheck = false;
    if (!data.email) delete data.email;

    if (!keys.length) {
      // Online form not switched on yet (no access key): offer email instead.
      var body = 'Parent: ' + data.name + '\nMobile: ' + data.phone + '\nChild: ' + (data.child_name || '') +
        '\nClass: ' + (data.class_applying_for || '') + '\n\n' + (data.message || '');
      var mail = 'mailto:' + form.getAttribute('data-fallback-email') + '?subject=' +
        encodeURIComponent('Admission enquiry') + '&body=' + encodeURIComponent(body);
      status.innerHTML = 'Online enquiries are not switched on yet. Please <a href="' + mail.replace(/"/g, '&quot;') +
        '">send this enquiry by email</a> or call the office.';
      return;
    }

    submit.disabled = true;
    status.textContent = 'Sending…';
    Promise.all(keys.map(function (key) {
      var payload = Object.assign({ access_key: key }, data);
      return fetch('https://api.web3forms.com/submit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify(payload)
      }).then(function (r) { return r.json(); }).then(function (j) { return !!(j && j.success); })
        .catch(function () { return false; });
    })).then(function (results) {
      submit.disabled = false;
      if (results.some(Boolean)) { showThanks(); }
      else { status.textContent = 'Sorry, the enquiry could not be sent. Please try again or call the office.'; }
    });
  });

  window.VNLForm = { normalisePhone: normalisePhone };
})();
