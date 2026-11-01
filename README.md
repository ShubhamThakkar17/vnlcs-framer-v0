# Veer New Look Central School website

Static-first PHP site for Hostinger shared hosting. See `BRIEF.md` for the spec and `CLAUDE.md` for working rules.

## Local preview
```bash
php -S localhost:8000
```

Style tile: http://localhost:8000/styletile.html

## Tools
- `node tools/contrast.js` – WCAG AA check of every text/background pair in both themes (reads `assets/css/tokens.css`; exits 1 on failure).
- `node tools/shots.js styletile.html [more paths]` – full-page screenshots in both themes at 375 px and 1440 px into `reports/screens/` (needs the local server running).
- `node tools/reference-shots.js` – screenshots of the two visual-reference templates into `/reference/` (gitignored).

Deploy instructions arrive in Phase 7.
