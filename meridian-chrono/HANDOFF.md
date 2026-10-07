# Meridian Chrono Group — store prototype handoff

Updated October 7, 2026. Working prototype, not yet published.

## What's here
- `index.html` — the full store in one file. Open it in any browser; no build step.
- `logo/` — emblem and lockup (SVG + 1024 px PNG).

Everything editable sits at the top of the script: `siteConfig`, `founder`, `policyContent`, `watches`.

## Sourced from Meridian's Instagram (@meridianchronogroup, read Oct 7, 2026)
- Bio: Rolex, Patek Philippe, AP, F.P. Journe; Buy, Sell, Trade, Source; +1 (602) 492-5326; info@meridianchrono.com; Arizona (posts tagged Scottsdale); 18.3K followers.
- Founder post (June 8, 2026): data-science background, Switzerland watchmaking dream, "less grey market gamble, more curated relationship."
- Listings used: Submariner 126610LV (unworn, white tag), Datejust 41 two-tone ($20,000 shipped insured / $19,750 local), Oyster Perpetual 41 (2026), Vacheron Overseas blue, Day-Date blue/gold, Datejust diamond dial, Submariner 16613 (sold), GMT-Master II 126710BLNR (marked sold; eBay listing ended).
- Founder facts from Sarvesh: passion since the late 2000s, ten luxury watches, 50–70 pieces in inventory. Note: Rushik's post says his collection reached "20+ pieces."

## Must replace before launch
- [ ] **Sample listings** (`source: 'sample'`): 13 placeholder watches and their prices. Replace with live inventory, then set `demoMode: false` (which also hides any remaining samples).
- [ ] **Photos**: staging uses openly licensed Wikimedia Commons photos as *representative* images (labelled on each watch page, credited at `#/credits`). Replace with Meridian's own photos by filling each watch's `photos` array; the Vacheron and Journe Chronomètre Bleu still use illustrations.
- [ ] Confirm status and price of each Instagram-sourced watch (some may have sold).
- [ ] Founder portrait (`founder.photo`) and Rushik's approval of the About copy.
- [ ] Purchase terms: authentication, warranty, payment, returns (`policyContent`) — hidden until supplied.
- [ ] **Quality check wording** (`qualityFlow`): confirm it matches the real process, including who does the independent check.
- [ ] **Terms and Privacy** (`#/terms`, `#/privacy`): basic drafts written to match how the site works. Have them reviewed by Meridian's legal adviser, then set `legal.draft: false`.
- [ ] **Instagram feed** (`instagramPosts`): four post shortcodes shown in the footer. Swap in newer posts any time.
- [x] **Brand logos** (`logos/`): official logos from each brand's own website header (Rolex crown, Patek Philippe, Audemars Piguet, Vacheron Constantin, F. P. Journe), shown on Shop by brand, the About brand cards and the ticker. Added on Sarvesh's confirmation that Meridian has approval from all five brands; keep those approvals on file.
- [ ] A server endpoint for inquiries and purchase requests (`inquiryEndpoint`). No payment processing is built in; checkout is a request that Meridian follows up.

## Added October 7
- Home page "Every watch is checked twice" flow: in-house inspection, independent check, honest listing, genuine-only promise (no fakes or replicas). Also in the trust bar, product pages and Client care FAQ.
- Footer: embedded Instagram posts and a world-time strip (Scottsdale, New York, London, Geneva, Dubai, Hong Kong, Tokyo) with day/night dials.
- Terms of use and Privacy notice pages, linked from the footer and under every form.
- Tighter spacing: product details now sit under the gallery, smaller founder block, shorter hero, Instagram band moved into the footer.

## Added October 7 (round 2)
- Representative photos for 19 watches (Wikimedia Commons, credited) plus Meridian's own Instagram post in the gallery of each Instagram-sourced watch.
- Compare up to 3 watches side by side (`#/compare`), with differences highlighted.
- Make an offer on any available watch; Recently viewed on watch pages; "Get first call on new arrivals" alert sign-up on the home page.
- Floating "Text us" button; Photo credits page.

## Added October 7 (round 3)
- Home: hero photo slideshow (`heroSlides`), moving brand ticker, "Watch of the week" spotlight (`spotlightWatchId`), stats band, gentle scroll-in animations (off when the visitor prefers reduced motion).
- About: Rushik's own founder reel embedded (`founder.video`) until a portrait is supplied, "The houses we know best" (Rolex, Patek Philippe, AP, Vacheron Constantin, F. P. Journe), and "How buying from Meridian works".

## Typography
- Site text: Manrope (Google Fonts), falling back to Avenir Next / Segoe UI / system sans.
- Logo wordmark only: Bodoni Moda.

## Verified (headless Chromium)
- Navigation works when opened directly, and inside sandboxed previews such as file viewers or embedded frames (all 80 internal link targets crawled, none broken).
- Every page loads at 320, 390, 768 and 1440 px with no horizontal overflow and no script errors.
- Faceted filters (brand, price, condition, box and papers, size, metal, dial) combine, show live counts, sync to the URL, and clear individually or all at once. Search matches brand, model, reference and nickname. Sold watches sort last and are hidden unless requested.
- Bag: add, remove, count badge, request-to-buy checkout with insured shipping vs local pickup totals; address fields hide for pickup. Only watch IDs are stored in the browser.
- Price-on-request watches have no Add to bag; inquiries carry the watch ID. Sold watches offer "Find me one like this."
- Mega menu, mobile menu, filter drawer and dialogs work by keyboard; Escape closes them and focus returns.
- Forms reject missing fields, bad email and phone; demo success says "Preview complete. No request has been sent."
