# Meridian Chrono Group website — start here

You're picking up the new website for **Meridian Chrono Group**, a luxury watch dealer in Scottsdale, Arizona (founder: Rushik Dave; Instagram @meridianchronogroup). The design, store and WordPress theme are built. What's left is mostly **real content** and **going live**.

If you use Claude, upload this whole zip and paste the prompt at the bottom.

---

## Where things are

| | |
|---|---|
| Staging site | https://aeroassistindustries.github.io/lux-auto-rentals/meridian-chrono/ |
| GitHub | `AeroAssistIndustries/lux-auto-rentals`, folder `meridian-chrono/` (owner: Sarvesh Joshi) |
| The whole site | `index.html` — one file, no build step. Open it in a browser to run it. |
| Meridian logo | `logo/` (SVG + PNG, ink and ivory versions) |
| Brand logos | `logos/` (Rolex, Patek Philippe, AP, Vacheron Constantin, F. P. Journe) |
| WordPress | `wordpress/dist/meridian-chrono-theme.zip` + `wordpress/INSTALL.md` |
| Running notes | `HANDOFF.md` (sources, decisions, change log) |

## How the site works
- Single-page app with hash routes: `#/watches`, `#/watch/<id>`, `#/sold`, `#/saved`, `#/checkout`, `#/compare`, `#/sell-trade`, `#/source`, `#/about`, `#/client-care`, `#/contact`, `#/terms`, `#/privacy`, `#/credits`.
- **Everything editable is in plain JavaScript objects at the top of the `<script>` in `index.html`** (search for these names):
  - `siteConfig` — phone, email, location, hours, Instagram, `heroSlides` (home slideshow), `spotlightWatchId` ("Watch of the week"), and switches: `demoMode` (show sample watches), `showPreviewBanner`, `formsLive`, `inquiryEndpoint`.
  - `founder` — About page story, `photo`, `video`.
  - `qualityFlow` — the "checked twice" steps (in-house, then independent check, genuine only).
  - `policyContent` — authentication, warranty, payment, returns. All `null` now, so those sections are hidden.
  - `legal` — Terms/Privacy date and `draft: true` flag.
  - `houses`, `brandLogos` — brands shown on Shop by brand, About and the logo ticker.
  - `instagramPosts` — four Instagram post codes shown in the footer.
  - `photoLibrary` — representative watch photos from Wikimedia Commons (credited on `#/credits`).
  - `watchInstagram` — Meridian's own Instagram post for each Instagram-sourced watch.
  - `watches` — the inventory. Each watch has `id`, brand, model, reference, year, `priceUsd` (or price on request), condition, box/papers, specs, `status` (`available` / `reserved` / `sold`), `photos`, and `source` (`'instagram'` or `'sample'`).
- Browser storage holds only watch IDs (saved, bag, compare, recently viewed). No personal data is stored in the browser.
- Fonts: Manrope for all text, Bodoni Moda for the logo wordmark only.
- Forms: on GitHub Pages they show "Preview complete. No request has been sent." On WordPress they email Meridian once switched on (see `wordpress/INSTALL.md`).

## What's left to finish

**Content (needs Rushik / Meridian):**
1. **Real inventory.** 13 of the watches are placeholders (`source: 'sample'`) with made-up prices. Replace them with Meridian's actual 50–70 pieces, then set `demoMode: false` (or untick "Show sample watches" on WordPress).
2. **Confirm the Instagram-sourced watches** (`source: 'instagram'`) are still for sale at those prices. Some may have sold.
3. **Real photos.** Each watch currently uses a representative Wikimedia photo (labelled "Representative photos"). Add Meridian's own photos to each watch's `photos` array. Once a watch has its own photos, remove its `photoLibrary` entry so the credit disappears too.
4. **Founder portrait** (`founder.photo`) and Rushik's sign-off on the About copy. Note: his Instagram says his collection reached "20+ pieces"; the site says ten luxury watches (per Sarvesh). Confirm which to use.
5. **Purchase terms** in `policyContent`: authentication, warranty, payment methods, returns.
6. **Quality-check wording** in `qualityFlow`: confirm it matches the real process and who does the independent check.

**Legal:**

7. Have a lawyer review `#/terms` and `#/privacy`, then set `legal.draft: false`.
8. Keep the brand-logo approvals on file. Sarvesh confirmed on Oct 7, 2026 that Meridian has approval from all five brands; the footer carries a trademark notice saying Meridian is independent and not affiliated.

**Going live:**

9. Pick hosting: WordPress (follow `wordpress/INSTALL.md`) or any static host (upload `index.html`, `logos/`, `logo/`; forms then need an endpoint in `siteConfig.inquiryEndpoint`, such as Formspree, plus `formsLive: true`).
10. Point the domain (meridianchrono.com) at the host and turn on HTTPS.
11. Turn on form emails, install an SMTP plugin on WordPress, and test every form once.
12. Optional: add analytics (paste the snippet before `</head>` in `index.html` / `app.html`) and a favicon file.

## Rules of the road
- **Keep the design as is** unless Sarvesh or Rushik ask: dark green ink `#152724`, gold `#A88A55`, ivory, Manrope text.
- **No fakes or replicas, ever** — the whole site promises genuine watches only.
- **Don't invent facts** about Meridian (prices, history, guarantees, certifications). Ask the owner.
- **Test on phone width (320–390 px) and desktop** after changes. There should be no sideways scrolling, and every link should open the right page.
- **Never put card details on the site.** Checkout is a purchase *request* that Meridian follows up personally.

## Updating the WordPress zip after edits
From the repo root, run:
```
bash meridian-chrono/wordpress/build-wp-theme.sh
```
It copies `index.html` into the theme as `app.html`, adds the logos and rebuilds `wordpress/dist/meridian-chrono-theme.zip`. It needs `php` and `zip`.

---

## Prompt to paste into Claude

> I'm taking over the Meridian Chrono Group website. The attached zip has the complete site (`index.html`), logos, a WordPress theme and notes. Read `START-HERE.md` and `HANDOFF.md` first. Keep the existing design and structure; edit content through the config objects at the top of the script in `index.html`. Don't invent facts about the business: ask me for anything missing. First, summarize what's left to finish and what you need from me. Then we'll work through it, starting with replacing the sample inventory with the real watches I'll give you.
