# Sanity Studio: Veer New Look notice board

Staff use this Studio to post notices, CBSE circulars, newsletters and photo albums.
It is deployed to Sanity's own hosting (`https://veernewlook.sanity.studio`) and is **not** uploaded to Hostinger.

## First-time setup (KeshavCo)

```bash
cd sanity
npm install
npx sanity login
```

Then:

1. Create a project at https://www.sanity.io/manage ("Veer New Look", dataset `production`). Put its project ID
   in `sanity/.env` as `SANITY_STUDIO_PROJECT_ID=xxxxxxxx` and in the website's `inc/config.php`
   (`'sanity' => ['project_id' => 'xxxxxxxx', ...]`).
2. Make the dataset public (read-only): `npx sanity dataset visibility set production public`.
3. Deploy the Studio: `npm run deploy`.
4. Invite staff: `npx sanity users invite name@example.com --role editor`.
5. Open the Studio, go to **Site settings**, set the admission session, and publish.
6. Bring in the notices the site already shows (from `data/notices.json`):

   ```bash
   cd ..
   php tools/notices-to-sanity.php > sanity/import/notices.ndjson
   cd sanity
   npx sanity dataset import import/notices.ndjson production --missing
   ```

   Published notices arrive published. Drafts ("draft" = waiting for school details, "check" = official
   link not yet verified) arrive as Studio drafts, so nothing unchecked goes live. `--missing` skips any
   notice already in Sanity, so running it twice is safe.

The website reads published content from the Sanity CDN and caches it for 10 minutes, so a new notice
appears on the site within 10 minutes. No token is needed or used in the browser.

See `../STAFF-GUIDE.md` for the 2-minute guide for school staff.
