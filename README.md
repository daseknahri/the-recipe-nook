# UNTITLED RECIPES SITE — placeholder brand (Style A self-seeding WordPress site)

> **STATUS (2026-11-06): scaffold built + local QA passed. NOT launched.**
> Brand name pending owner pick — the placeholder `UNTITLED RECIPES SITE`
> lives in `content/site-profile.json` (the single config place) plus the
> spots listed in `SITE-CARD-DRAFT.md`. No GitHub repo, no DNS, no Coolify
> project, no deployment yet — those are owner-gated launch steps.

A tips & recipes publication on the network pattern shared with Dales &
Cobbles and Storyline UK: Docker WordPress, a self-seeding germ pattern,
the Automation Hamri plugin (build-v9 9.48.0, the kepoli recipe engine) and
the viral-reader theme (1.9.37) with a bespoke child theme, `recipes-site`.

## Quick facts

- **Stack:** `Dockerfile` (wordpress:php8.3-apache) + `docker-compose.yml`
  (prod) + `docker-compose.local.yml` (local override, port **8098**).
  Entry point `site-init.sh`: syncs code, installs WordPress, runs
  `seed/seed.php` from `content/site-profile.json`, pre-warms brand assets.
- **Seeded shape:** 6 pillars — Breakfast & Brunch (default), Main Dishes,
  Desserts & Baking, Quick & Easy, Kitchen Tips, Cooking Guides — 15 empty
  tag hubs, 7 trust pages, menus, ads.txt (network getemoji-family line),
  AdSense OFF (`ADSENSE_ENABLE=0`), GA/measurement placeholder empty,
  **zero posts**.
- **Language/market:** en_GB, Europe/London — a *default* (owner has not
  specified); one value in the profile (`language` / `timezone`).
- **Recipe-native:** the plugin renders the visible recipe card +
  schema.org/Recipe JSON-LD from `_wpap_recipe_*` meta, populated by
  Bulk-Import Contract v1.2 **flat** recipe fields (`type:"recipe"`,
  `ingredients[]`, `steps[]`, `prep`, `cook`, `servings`, `course`). The
  child theme deliberately does not duplicate card/schema; it adds the
  presentation layer (next-recipe bar, "Did you make this one?" block).
  Theme mod `vr_noun` = `recipe`. Pinterest save stays ON (recipes are
  Pinterest-native).
- **Content ready:** `content/first-batch/` — 8 original recipes + 2 guides
  with generated photography, contract-validated (0 fail / 0 warn), NOT
  published. See its README for the menu and the publish route.

## Local run

```powershell
Copy-Item .env.example .env   # then fill real secrets ($-free salt charset)
docker compose -f docker-compose.yml -f docker-compose.local.yml up -d --build
# http://localhost:8098 — the seed runs on first boot; watch for "[seed] site seeded"
docker compose -f docker-compose.yml -f docker-compose.local.yml down  # volumes persist
```

## Files of interest

- `content/site-profile.json` — brand (placeholder), domain (placeholder),
  pillars, hubs, pages, feature flags. The ONE config place.
- `seed/seed.php` — idempotent seeder (marker `rc_seed_version`).
- `wp-content/themes/recipes-site/` — child theme (placeholder warm palette;
  real palette arrives with the brand pass).
- `brand/` — placeholder brand pack (original generated art) + `brand_compose.py`.
- `SITE-CARD-DRAFT.md` — full site card + placeholder TODOs + launch remainder.
