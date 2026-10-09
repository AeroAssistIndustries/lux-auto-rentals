# Meridian Chrono Group — WordPress install guide

The file `meridian-chrono-theme.zip` is a WordPress theme that runs the full Meridian store: home page, shop with filters, watch pages, bag and purchase request, sell or trade, find a watch, About, Client care, Terms and Privacy. It looks and works exactly like the staging site.

## What you need
- WordPress 6.0 or newer, PHP 7.4 or newer (any normal host: WP Engine, SiteGround, Bluehost, GoDaddy, Kinsta and so on).
- An administrator login.

## 1. Install the theme
1. In WordPress, go to **Appearance › Themes › Add New Theme › Upload Theme**.
2. Choose `meridian-chrono-theme.zip` and click **Install Now**.
3. Click **Activate**.

Visit the site's home page. The Meridian store should load straight away.

> If the upload fails with "The uploaded file exceeds the upload_max_filesize", ask the host to raise the limit to 8 MB, or upload the unzipped `meridian-chrono` folder to `wp-content/themes/` by SFTP and activate it under Appearance › Themes.

## 2. Turn on form emails
Out of the box, forms show "Preview complete. No request has been sent." so nothing goes out by accident.

1. Go to **Appearance › Customize › Meridian site**.
2. Tick **Send form submissions by email**.
3. Set **Send submissions to** (for example `info@meridianchrono.com`).
4. Click **Publish**.

Every form (watch inquiry, make an offer, purchase request, sell or trade, find a watch, contact, new-arrivals alert) is now emailed to that address. The subject reads like `[Meridian Chrono Group] New purchase request from Jane Smith`, and replying goes straight to the customer.

**Make email reliable.** Many hosts send WordPress mail poorly and it lands in spam. Install a free SMTP plugin such as **WP Mail SMTP** or **FluentSMTP**, connect it to the mailbox Meridian uses (Google Workspace, Microsoft 365 and so on), and send yourself a test from each form.

Built-in protection: submissions are accepted only from the site itself, limited to 5 per visitor every 10 minutes, and a hidden spam trap drops most bots. Purchase requests are requests only; no card details are taken.

## 3. Swap in the real inventory
Watches, prices, photos and all page text live at the top of one file: `app.html` in the theme folder.

1. Open it with the host's **File Manager** or SFTP at `wp-content/themes/meridian-chrono/app.html`. (WordPress's built-in Theme File Editor doesn't open `.html` files.)
2. Near the top of the `<script>` section you'll find, in order:
   - `siteConfig`: phone, email, address, hours, Instagram.
   - `founder`: About page copy and portrait.
   - `policyContent`, `qualityFlow`, `legal`: purchase terms, the two-step check wording, Terms/Privacy draft flag.
   - `watches`: one entry per watch (brand, model, reference, year, price, condition, box and papers, `photos`, `status: 'available' | 'reserved' | 'sold'`).
3. For real listings, set `source: 'instagram'` (or anything other than `'sample'`), and add photo URLs to `photos`. Upload photos through **Media › Add New** and copy each file URL.
4. When the real inventory is in, untick **Show sample watches** in **Appearance › Customize › Meridian site**. Every watch marked `source: 'sample'` disappears.

Keep a copy of `app.html` before editing; if a comma goes missing the store won't load, and putting the copy back fixes it.

## 4. Before going live
- [ ] Have Meridian's legal adviser review the Terms and Privacy pages, then set `legal.draft: false` in `app.html`.
- [ ] Replace the representative (Wikimedia Commons) photos with Meridian's own; the Photo credits page lists what's still borrowed.
- [ ] Add the founder portrait (`founder.photo`) and purchase terms (`policyContent`).
- [ ] Confirm the brand-logo approvals are on file.
- [ ] Submit each form once and check the email arrives.
- [ ] Add an SSL certificate (most hosts do this free) so the address starts with `https://`.

## Good to know
- **Page addresses** use `#` (for example `/#/shop`, `/#/watch/rolex-submariner-126610lv`). WordPress permalink settings don't affect them, and shared links open the right page.
- **WordPress pages and posts** you create aren't shown; the theme serves the store for every address. The WordPress admin (`/wp-admin`) works normally.
- **Plugins that inject into pages** (analytics, chat widgets, SEO plugins, cookie banners) don't run on the store page because it's served as a finished document. Add analytics by pasting its snippet just before `</head>` in `app.html`.
- **Caching plugins** are fine. After editing `app.html` or the Customizer settings, clear the cache.
- **Updating the theme**: upload a newer zip the same way and choose **Replace current with uploaded**. This overwrites `app.html`, so make inventory edits in the copy you upload.

## Theme files
| File | Purpose |
|---|---|
| `app.html` | The whole store (same as the staging site). |
| `index.php` | Serves `app.html`, fixes logo paths and passes the Customizer settings to the store. |
| `functions.php` | Customizer settings and the form endpoint (`/wp-json/meridian/v1/inquiry`), which emails submissions. |
| `logos/` | Brand logos used on the site. |
| `style.css`, `screenshot.png` | Theme name and the preview shown under Appearance › Themes. |
