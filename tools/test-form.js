// Browser test of the enquiry form with Web3Forms mocked (nothing is really sent).
// Usage: node tools/test-form.js   (local server running; config/secrets.php with two test keys)
const { chromium } = require('playwright');
const BASE = process.env.BASE_URL || 'http://127.0.0.1:8000';
let fail = 0;
const check = (label, ok) => { console.log((ok ? '  pass  ' : '  FAIL  ') + label); if (!ok) fail++; };
(async () => {
  const b = await chromium.launch();
  const ctx = await b.newContext();
  const page = await ctx.newPage();
  const sent = [];
  await page.route('https://api.web3forms.com/submit', async route => {
    sent.push(JSON.parse(route.request().postData()));
    await route.fulfill({ status: 200, contentType: 'application/json', body: '{"success":true,"message":"ok"}' });
  });
  await page.goto(BASE + '/admissions/#enquire');
  console.log('Enquiry form');

  await page.click('#enquiry-form [type=submit]');
  check('empty submit shows parent-name error', await page.isVisible('#e-parent'));
  check('empty submit shows phone error', (await page.textContent('#e-phone')).includes('mobile number'));
  check('focus moves to the first invalid field', await page.evaluate(() => document.activeElement.id === 'f-parent'));
  check('aria-invalid set on invalid fields', (await page.getAttribute('#f-phone', 'aria-invalid')) === 'true');

  await page.fill('#f-parent', 'Test Parent');
  await page.fill('#f-phone', '12345');
  await page.click('#enquiry-form [type=submit]');
  check('non-Indian number rejected', (await page.textContent('#e-phone')).includes('10-digit'));

  await page.fill('#f-phone', '+91 98765-43210');
  await page.fill('#f-email', 'not-an-email');
  await page.click('#enquiry-form [type=submit]');
  check('bad email rejected', (await page.textContent('#e-email')).includes('email'));
  check('nothing sent while invalid', sent.length === 0);

  await page.fill('#f-email', 'parent@example.com');
  await page.selectOption('#f-class', 'LKG');
  await page.click('#enquiry-form [type=submit]');
  await page.waitForSelector('.form-thanks:not([hidden])');
  check('valid form shows the chalk thank-you state', await page.isVisible('.form-thanks'));
  check('sent once per access key (both inboxes)', sent.length === 2 && sent[0].access_key !== sent[1].access_key);
  check('phone normalised to 10 digits', sent[0].phone === '9876543210');
  check('class and parent name included', sent[0].class_applying_for === 'LKG' && sent[0].name === 'Test Parent');

  // Honeypot: a bot fills the hidden field
  sent.length = 0;
  await page.goto(BASE + '/admissions/?honeypot=1#enquire');
  await page.evaluate(() => { document.querySelector('[name=website]').value = 'http://spam.example'; });
  await page.fill('#f-parent', 'Bot'); await page.fill('#f-phone', '9876543210');
  await page.click('#enquiry-form [type=submit]');
  await page.waitForSelector('.form-thanks:not([hidden])');
  check('honeypot: bot sees thank-you but nothing is sent', sent.length === 0);

  // Campus tour CTA pre-fills the message
  await page.goto(BASE + '/admissions/?visit=1#enquire');
  check('campus tour link pre-fills the message', (await page.inputValue('#f-message')).includes('campus tour'));

  await b.close();
  console.log(fail ? `\n${fail} check(s) failed.` : '\nAll form checks passed.');
  process.exit(fail ? 1 : 0);
})();
