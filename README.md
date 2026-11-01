# Veer New Look Central School, Halol: website

Plain HTML, CSS, vanilla JavaScript and small PHP 8 files for Hostinger shared hosting.
No framework and no build step on the server. Notices come from Sanity, reels from Instagram.

- Spec: `BRIEF.md` · Working rules and design tokens: `CLAUDE.md`
- What the school still needs to send: `PLACEHOLDERS.md`
- Old-site redirects: `REDIRECTS.md` · Guide for school staff: `STAFF-GUIDE.md`

---

## Deploy to Hostinger (follow in order)

You need: hPanel access for **veernewlookschool.com**, the two school Gmail accounts, the two Instagram
accounts, and a computer with PHP 8 and Python 3 (only to build the zip).

### 1. Build the upload

```bash
tools/build-dist.sh
```

This creates `dist/veernewlookschool-site-YYYYMMDD.zip`. It contains only the live site: no Studio,
no tools, no notes, and never `config/secrets.php`.

### 2. Prepare Hostinger

1. hPanel → **Websites** → veernewlookschool.com → **Advanced → PHP Configuration**: choose **PHP 8.2 or newer**.
   Make sure the extensions **curl**, **json** and **mbstring** are ticked (they are by default).
2. hPanel → **Security → SSL**: install the free SSL certificate if it is not active yet.
3. Back up the old site: **Files → File Manager → public_html**, select everything, **Compress**, download the zip.
   Keep the old PDF folders (`2026_pdf`, `2021-pdf`, ...) for step 4.

### 3. Upload

1. In **File Manager → public_html**, delete the old site's files (keep your backup).
2. **Upload** the zip from step 1 into `public_html`, right-click it → **Extract** → into `public_html` itself.
3. Delete the zip. You should now see `index.php`, `.htaccess`, `about/`, `assets/` ... directly inside `public_html`.
4. Folder permissions: `data/`, `data/cache/` and `media/ig/` must be writable (755 is fine on Hostinger).

### 4. Documents (CBSE disclosure and old PDF links)

Upload PDFs into `public_html/docs/`.

- Old PDF links like `/2026_pdf/Fee Structure.pdf` redirect to `/docs/Fee Structure.pdf`, so copy the old
  PDFs into `docs/` with the **same file names**.
- The CBSE Disclosure page links each row automatically when its file exists. The expected names are listed
  in `REDIRECTS.md` (e.g. `affiliation-letter.pdf`, `fee-structure.pdf`). Then set `$lastUpdated` at the
  top of `cbse-disclosure/index.php`.

### 5. Secrets

In File Manager, open `config/`, copy `secrets.example.php` to **`secrets.php`** and fill it in
(steps 6 to 8 explain each value). `secrets.php` is blocked from the web and never committed.

```php
return [
    'web3forms_keys'   => ['KEY-FOR-veer.newlook@gmail.com', 'KEY-FOR-veernewlookoffice@gmail.com'],
    'instagram_tokens' => ['vnlcs_halol' => 'IGAA...', 'veernewlookschoolhalol' => 'IGAA...'],
    'alert_email'      => 'support@keshavco.com',
];
```

### 6. Enquiry form (Web3Forms, free)

1. Go to https://web3forms.com, enter **veer.newlook@gmail.com**, and confirm the email. Copy the access key.
2. Do the same with **veernewlookoffice@gmail.com**.
3. Put both keys in `web3forms_keys`. The form sends each enquiry once per key, so both inboxes get it.

Until keys are added, the form offers to send the enquiry by email instead, so it never fails silently.
Without JavaScript, the form still posts to Web3Forms (first key) and comes back to a thank-you message.

### 7. Notices: Sanity (free plan)

Full steps are in `sanity/README.md`. In short:

1. Create a free project at https://www.sanity.io/manage (name it "Veer New Look", dataset `production`)
   and copy its **project ID**.
2. Put it in `sanity/.env` as `SANITY_STUDIO_PROJECT_ID=xxxxxxxx`, then:

```bash
cd sanity
npm install
npx sanity login
```

```bash
npx sanity dataset visibility set production public
```

```bash
npm run deploy
```

```bash
npx sanity users invite office@example.com --role editor
```

1. Put the same project ID into `inc/config.php` → `'sanity' => ['project_id' => 'xxxxxxxx', ...]` and upload that file.
2. In the Studio, open **Site settings**, set the admission session (e.g. 2027-28) and **Publish**.
3. Post a test notice. It appears on the site within **10 minutes** (cache time), with its own URL.

Until a project ID is set, the site shows clearly marked **[SAMPLE]** notices.

### 8. Instagram reels

Both accounts must be **Professional** (Business or Creator). In the Instagram app: Settings → Account
type and tools → Switch to professional account.

1. At https://developers.facebook.com create an app (type **Business**), add **Instagram → API setup with
   Instagram login**, and add both accounts as **Instagram testers** (accept the invitations in each account,
   Settings → Website permissions → Apps and websites).
2. For each account, click **Generate token**. This gives a long-lived token (60 days). Put the two tokens in
   `instagram_tokens` in `config/secrets.php`.
3. Test with a dry run (hPanel → **Advanced → SSH Access**, or run it once as a cron job and read the output email):

   ```bash
   php ~/domains/veernewlookschool.com/public_html/cron/instagram-fetch.php --dry-run
   ```

   You should see `@vnlcs_halol: ok (12 items)` and `@veernewlookschoolhalol: ok (12 items)`.
4. Run it once for real (without `--dry-run`). Reels now play on the home page and the Gallery.

Tokens are refreshed automatically by the daily cron job below (renewed when older than 30 days; the new token
is saved in `data/tokens.json`). If refreshing fails, an email goes to `alert_email`.

**Fallback (paid):** if the Instagram API becomes a problem, sign up for Behold Starter (about $10/month),
create a JSON feed for both accounts, then set in `inc/config.php`:

```php
'reels_source' => 'behold',
'behold_url'   => 'https://feeds.behold.so/XXXXXXXX',
```

### 9. Cron jobs

hPanel → **Advanced → Cron Jobs** → Custom. Replace `uXXXXXXXX` with your Hostinger username
(shown in File Manager's path bar).

| When | Command |
|---|---|
| Every hour (minute 7) | `/usr/bin/php /home/uXXXXXXXX/domains/veernewlookschool.com/public_html/cron/instagram-fetch.php` |
| Every day at 03:17 | `/usr/bin/php /home/uXXXXXXXX/domains/veernewlookschool.com/public_html/cron/token-refresh.php` |

Cron expressions: hourly `7 * * * *`, daily `17 3 * * *`.

### 10. Go-live checks

1. Open https://veernewlookschool.com in both themes on a phone. Try the menu, the Call bar and WhatsApp.
2. Submit a test enquiry and check **both** inboxes.
3. Check the old addresses and private folders from your computer:

   ```bash
   tools/test-htaccess.sh https://veernewlookschool.com
   ```

4. Google Search Console: add the domain and submit `https://veernewlookschool.com/sitemap.xml`.
5. Once the school confirms the public figures (students, teachers, classrooms, campus), set
   `'stats_confirmed' => true` in `inc/config.php`.

---

## Updating the site

- **Notices, newsletters, albums, admission session, emergency banner:** school staff, in Sanity Studio (`STAFF-GUIDE.md`).
- **Text and placeholders:** edit the page's `.php` file (each page is one file, e.g. `about/index.php`). Shared
  pieces are in `partials/` (header, footer, FAQ, steps, why grid). Contact details are in `inc/config.php`.
- **Photos:** put originals in `assets/img/src/` (never stock photos of children), then:

  ```bash
  cd tools && npm install && npm run images
  ```

  It writes AVIF, WebP and JPEG copies at 480, 960 and 1440 px, removes location data, and prints the
  `picture(...)` call to paste into `taped_photo()` in place of `null`.

## Local preview

```bash
php -S localhost:8000 inc/router.php
```

Then open http://localhost:8000 (the style tile is at `/styletile.html`). `inc/router.php` does the job of
`.htaccess` locally and on Vercel.

## Vercel previews

`vercel.json` runs the site on Vercel through the community `vercel-php` runtime (`api/vercel.php` →
`inc/router.php`). It is for previews only: Vercel's disk is read-only, so the Instagram cron does not run there.
Hostinger ignores these files, and they are not in the deploy zip.

## Checks and tools

Run them with the local server (or Apache) running. Most need Node with Playwright.

| Command | Checks |
|---|---|
| `node tools/contrast.js` | WCAG AA contrast of every token pair in both themes |
| `node tools/axe.js / /about/ ...` | axe accessibility: both themes, 375 and 1440 px (needs `axe-core`, in `tools/`) |
| `tools/lighthouse.sh [url]` | Lighthouse mobile for Home, Notice Board, Gallery |
| `node tools/test-site.js [url]` | titles, descriptions, JSON-LD, internal links, theme memory, no flash, reduced motion |
| `tools/test-htaccess.sh [url]` | 301s for every old URL, 404 for private files, headers, HTTPS and www rules |
| `php tools/test-notices.php` | Sanity pipeline end to end against a mock CDN (fetch, cache, render, stale fallback) |
| `node tools/test-form.js` | enquiry form validation, both inboxes, honeypot (Web3Forms mocked; needs test keys) |
| `tools/test-instagram.sh` | Instagram fetch and token refresh against a mock Graph API |
| `node tools/test-reels.js video.webm` | reels play in view, pause out of view, unmute, pause control, filters |
| `node tools/shots.js / /about/ ...` | screenshots in both themes at 375 and 1440 px (`reports/screens/`) |
| `node tools/make-icons.js` | favicon PNGs and the chalk Open Graph image |
| `tools/build-dist.sh` | the Hostinger zip |

## Folder map

```
index.php, 404.php, sitemap.php        pages served at /, missing pages, /sitemap.xml
about/ academics/ facilities/ gallery/ notices/ admissions/ cbse-disclosure/   one index.php each
notices/view.php                       one notice (/notices/<slug>)
api/notices.php                        latest notices as JSON
partials/                              head, header, footer, FAQ, steps, why grid
inc/                                   config, helpers, Sanity content, Instagram, router (blocked from the web)
assets/                                css, js, fonts, img, doodles
cron/                                  instagram-fetch.php, token-refresh.php (CLI only, blocked from the web)
config/                                secrets.example.php (+ secrets.php on the server only)
data/                                  sample content, instagram.json (written by cron), cache (private)
media/ig/                              cached reel videos and thumbnails
docs/                                  CBSE disclosure and other PDFs
sanity/                                Sanity Studio (deployed to sanity.studio, not to Hostinger)
tools/                                 developer checks and build scripts (not uploaded)
```
