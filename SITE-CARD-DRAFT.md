# SITE CARD (DRAFT) — UNTITLED RECIPES SITE *(placeholder brand — rename on brand pass)*

Filed: 2026-11-06 · Status: **SCAFFOLD BUILT + LOCAL QA PASSED — NOT LAUNCHED**
Sibling pattern: Dales & Cobbles / Storyline UK (Style A, plgin NEW-SITE-PLAYBOOK)

## Identity

- **Brand:** `UNTITLED RECIPES SITE` — **PLACEHOLDER**, owner pick pending.
  Single source: `content/site-profile.json` → `brand.name` (+ tagline,
  writer name, domain below).
- **Niche:** tips & recipes — original recipes, kitchen tips, cooking guides.
- **Market / language:** en_GB, timezone Europe/London. **DEFAULT, not
  owner-specified** — one config value in the profile if he chooses otherwise.
- **Domain (placeholder):** `untitledrecipes.getemoji.site` in the profile +
  prod compose `SITE_URL` default. Real subdomain chosen at launch.
- **Byline:** "UNTITLED RECIPES SITE Team" (seed `rc_writer_name` fallback).

## Where things live

- **Repo:** `C:\Users\Yassi\Desktop\plgin\recipes-site` (own git repo, branch
  `main`, **no remote**). Repo-local identity `daseknahri <daseknahri@gmail.com>`.
- **Local QA:** port **8098** (`docker-compose.local.yml`), containers
  `recipes-site-wordpress-1` / `recipes-site-db-1`, volumes
  `recipes-site_wp-data` / `recipes-site_db-data` (kept after teardown).
- **Secrets:** `.env` in repo root (gitignored) — generated fresh at scaffold
  time, salts in a `$`-free charset (the Storyline Compose lesson). Values
  are never printed in docs, commits or chat.

## Stack (vendored, unedited — verified byte-identical to the Storyline copy)

- Automation Hamri plugin **9.48.0** (build-v9 from plgin `main` —
  NOT from any BL branch) at `wp-content/plugins/automation-hamri/`.
- viral-reader theme **1.9.37** at `wp-content/themes/viral-reader/`.
- Child theme **`recipes-site` 1.0.0** (this repo's own code):
  prefixes `rc_` / `RC_` / `rcpc_`, page-cache dir `rc-page`,
  mu-plugins `rc-page-cache.php` + `rc-hardening.php`, seed marker
  `rc_seed_version`. Placeholder palette: warm cream `#FAF5EC`, herb green
  `#3E6B34`, terracotta `#C4552B`, gold `#D9A441` — **placeholder only**,
  the real palette comes with the brand pass. Storyline's cinematic dark
  skin filter was removed; the Pinterest save filter removal was also
  dropped (save stays ON — recipes are Pinterest-native).
- Front page: hero → **Trending recipes** rail (tag `trending`) →
  **Tips & tricks** rail (Kitchen Tips category) → topic band → latest
  grid. Rails render only when posts exist.

## Taxonomy (live-verified in local QA, 2026-11-06)

Categories (term IDs): 1 General (renamed uncategorized, unused) ·
**2 Breakfast & Brunch** (`breakfast-brunch`, also the default category) ·
**3 Main Dishes** · **4 Desserts & Baking** · **5 Quick & Easy** ·
**6 Kitchen Tips** · **7 Cooking Guides**.

Tag hubs (seeded empty): 8 British · 9 Italian · 10 Indian ·
11 Mediterranean · 12 Popular · 13 Trending · 14 Vegetarian · 15 Vegan ·
16 Budget · 17 Slow Cooker · 18 Air Fryer · 19 One-Pan · 20 Weeknight ·
21 Weekend · 22 Sunday Roast.

Trust pages (all seeded, 200-verified): About, Contact, Privacy Policy,
Terms, Cookie Policy, Editorial Policy, Corrections. The Editorial Policy
carries the **recipe-testing honesty standard**: no invented "tested 100
times" / "perfect / foolproof" claims, times and yields stated plainly,
UK measurements first, no medical or health claims.

## Recipe wiring (why this site is recipe-native)

- build-v9's `includes/seo-schema.php` renders the **visible recipe card**
  (the viral-reader skin presents it as `.vr-recipe-card`) and the
  **schema.org/Recipe JSON-LD** from `_wpap_recipe_*` post meta; it
  dedup-guards against dedicated recipe plugins (none installed, by design).
- `wpap_publish_article` fills that meta from the **flat** Bulk-Import
  Contract v1.2 §2.3 fields: `type:"recipe"` + `ingredients[]`, `steps[]`,
  `prep`, `cook`, `servings`/`yield`, `course` → `recipeCategory`. A nested
  `recipe{}` object is build-final (panrecipe) only and is ignored by
  build-v9 — do not use it in bundles for this site.
- Child-theme layer (mirrors the deutsche-rezepte child pattern): end-of-post
  block ("Did you make this one?" + follow line into Kitchen Tips), the
  next-post bar reads `_wpap_recipe_on` and labels itself "Next recipe",
  "Keep cooking" progress bar, byline from the profile writer name.
- **Proof (local QA):** the first-batch bundle was imported through the same
  `wpap_publish_article` path the seed uses; the shepherd's pie page rendered
  the card (Prep 25 min / Cook 50 min / Total 1 hr 15 min, ingredients,
  method) and Recipe JSON-LD containing `recipeIngredient`; the knife guide
  correctly had no card. Test posts + attachments were then permanently
  deleted; stack re-verified at 0 posts / 0 attachments.

## QA evidence (2026-11-06, local stack, pre-teardown)

- Seed log clean: `[seed] site seeded`, `[site-init] seed done`, card icon
  WebP conversions ran; the only notice was "site kit download failed"
  (no container internet for the optional Site Kit zip; retried next start —
  same class of benign notice as the siblings).
- Home 200; all 6 category archives 200; all 7 trust pages 200.
- Homepage: `lang="en-GB"`, placeholder title present, hero + intro present,
  1 JSON-LD block, parses.
- ads.txt served byte-exact: the network getemoji-family line.
- Active theme `recipes-site` 1.0.0 on viral-reader 1.9.37; timezone
  Europe/London; WPLANG en_GB; `vr_noun` theme mod = `recipe`.
- `php -l` clean: seed.php, child functions.php / front-page.php /
  header.php, both mu-plugins, advanced-cache.php.
- One defect found and fixed during QA: a string-concatenation typo in the
  child theme's next-recipe eyebrow (commit `58fac96`); note the Docker
  image bakes the code in — a rebuild, not a restart, is needed after code
  changes.

## Content

- `content/first-batch/` — 8 recipes + 2 guides, original text, UK
  measurements (grams, °C + gas mark), generated photography (own AI art
  only, no stock — network rule). Contract-validated with
  `content-pipeline/validate.mjs` + the bundle's own
  `profile-recipes-site.json`: **0 fail · 0 warn**. Word counts 470-537
  (recipes), 583 / 802 (guides). **Not published** — see the folder README
  for the menu and the Bulk-ZIP publish route. Never copy the folder into
  `data/bundles/` (auto-imports on boot).

## Placeholder-brand TODO (one pass when the owner picks the name)

1. `content/site-profile.json`: `brand.name`, `brand.writer`, tagline if
   desired, `domain`, `seo.ogImage` / logo file names if renamed.
2. Child theme header comment (`style.css` Theme Name) + site-init.sh
   install title + README/SITE-CARD titles (cosmetic).
3. Regenerate brand art with the real name: `brand/brand_compose.py`
   (mark + hero are name-free; logos/OG carry the placeholder wordmark).
4. Real palette pass over the child theme tokens (placeholder palette is
   deliberately warm/neutral).
5. Rebuild the local stack once; the seed applies the new name everywhere
   (it is profile-driven; a fresh DB reseeds cleanly).

## Launch remainder (ALL owner-gated, in order)

1. Brand pass (above) → 2. GitHub repo under `daseknahri/` + push →
3. Hostinger DNS A record on getemoji.site → 4. Coolify project + compose
app on the VPS → 5. Deploy + live verification (sibling checklist) →
6. Publish first batch (Bulk-ZIP) → 7. AdSense Add site (after content) →
8. GSC + sitemap → 9. SMTP + contact-email confirmation → 10. File the
plgin site card (`docs/sites/`) as its own backlog item, like BL-125 did
for Dales & Cobbles.

## Git history at filing

- `083d431` chore: scaffold new Style A site (germ pattern)
- `14acae2` chore(vendor): viral-reader 1.9.37 + build-v9 9.48.0 from plgin main
- `0d37c74` feat(theme): recipes-site child theme 1.0.0 (placeholder palette)
- `1b34748` feat(brand): placeholder brand pack + site profile
- `58fac96` fix(theme): repair next-recipe eyebrow concatenation in end block
- (content + docs commits follow)
