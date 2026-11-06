# SITE CARD — The Recipe Nook

Filed: 2026-11-06 · Status: **LAUNCHED + LIVE** — https://therecipenook.getemoji.site
Canonical card: plgin `docs/sites/the-recipe-nook.md` (filed as BL-136,
branch `muse/BL-136-the-recipe-nook-site-card`). This repo-local file is the
short summary; the plgin card is the network source of truth.
Sibling pattern: Dales & Cobbles / Storyline UK (Style A, plgin NEW-SITE-PLAYBOOK)

## Identity

- **Brand:** The Recipe Nook — picked by Muse from the owner's shortlist
  under his branding delegation (2026-11-06). Single source:
  `content/site-profile.json` → `brand.name` (+ tagline, writer, domain).
- **Niche:** tips & recipes — original recipes, kitchen tips, cooking guides.
- **Market / language:** en_GB, timezone Europe/London.
- **Domain:** `therecipenook.getemoji.site` (free subdomain, Dales pattern).
- **Byline:** "The Recipe Nook Team".

## Where things live

- **Repo:** github.com/daseknahri/the-recipe-nook (public); local
  `C:\Users\Yassi\Desktop\plgin\recipes-site`, branch `main`.
  Brand pass commit `360340e` was the launch deploy commit.
- **Coolify:** project `the-recipe-nook` (uuid sbaacse5thceq6bekxzt9gow),
  production env zqoy8il5ksjex8amtnscm7u9, app uuid
  **abthhpkcojpznuyultnocxjj** (domain on the `wordpress` service, port 80);
  first deployment j6cvmvxd7ub4bcszdu53x7lh Finished. **DNS:** A
  `therecipenook` → 85.31.239.111 (TTL 300), Hostinger getemoji.site zone.
- **Local QA:** port **8098** (`docker-compose.local.yml`), volumes
  `recipes-site_wp-data` / `recipes-site_db-data` (kept after teardown).
- **Secrets:** `.env` in repo root (gitignored), salts in a `$`-free charset.
  The LIVE wp-admin password is Coolify's server-side
  `SERVICE_PASSWORD_WPADMIN` only; values never printed in docs/commits/chat.

## Stack (vendored, unedited)

- Automation Hamri plugin **9.48.0** (build-v9 from plgin `main`).
- viral-reader theme **1.9.37** + child theme **`recipes-site` 1.0.0**
  (prefixes `rc_` / `RC_` / `rcpc_`, page-cache dir `rc-page`, mu-plugins
  `rc-page-cache.php` + `rc-hardening.php`, seed marker `rc_seed_version`).
- Palette: cream `#FAF5EC`, herb green `#3E6B34`, terracotta `#C4552B`,
  gold `#D9A441`. Brand media: own AI-generated only (spoon-and-rosemary
  mark, kitchen-table hero) composed via `brand/brand_compose.py`.
- Front page: standing brand hero → Trending recipes rail (tag
  `trending`) → Tips & tricks rail (Kitchen Tips category) → topic
  band → latest grid. The template consumes the newest post as its hero
  slot (excluded from grid/rails) — keep a RECIPE newest in each batch.
- Pinterest save ON (recipes are Pinterest-native). ADSENSE off
  (`ADSENSE_ENABLE=0`), GA off; ads.txt = the getemoji-family publisher line.

## Recipe wiring

build-v9 renders the visible recipe card + schema.org/Recipe JSON-LD from
the FLAT Bulk-Import fields (`type:"recipe"`, `ingredients[]`, `steps[]`,
`prep`, `cook`, `servings`, `course`). A nested `recipe{}` object is
build-final-only and ignored here. Other types render as plain articles.

## Taxonomy (live-verified 2026-11-06)

Categories: 1 General (unused) · **2 Breakfast & Brunch** (default) ·
**3 Main Dishes** · **4 Desserts & Baking** · **5 Quick & Easy** ·
**6 Kitchen Tips** · **7 Cooking Guides**. Tag hubs: 8–22 (13 = Trending,
drives the homepage Trending rail). Trust pages: About, Contact, Privacy
Policy, Terms, Cookie Policy, Editorial Policy (carries the recipe-testing
honesty standard), Corrections.

## Content

- **Batch 01 PUBLISHED 2026-11-06** via the Coolify container terminal
  (eval-file replica of `wpap_ajax_bulk_publish_zip` core): created 10,
  skipped 0 — 8 recipes (post IDs 27–41) + 2 guides (43, 45), author
  `editor` (ID 1). Source bundle: `content/first-batch/` (validated
  0 fail / 0 warn). Close-out: `wp rewrite flush` + `rc-page` purge;
  guides' dates set just before the recipes (hero-slot rule above);
  3 recipes tagged Trending.
- ⚠️ Never copy `content/first-batch/` into `data/bundles/` — that
  directory auto-imports on container boot.

## Pending (owner)

AdSense Add site + review (after content; separate go), GSC, SMTP.
NO Facebook Page for this site (account Page-creation cooldown;
Pinterest is the channel).
