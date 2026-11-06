# First content batch — ready to publish (NOT published)

Batch: **10 items** — 8 recipes + 2 guides — all original, written for this site.
Validated 2026-11-06 against `content-pipeline/validate.mjs` with
`profile-recipes-site.json` (this folder): **0 fail · 0 warn**.

## Format

`posts.json` is a top-level array in Bulk-Import Contract v1.2 shape (build-v9
9.48.0 `wpap_publish_article`):

- `id` = planned slug = **dedup key** (re-import is safe; existing slugs skip).
- `category` = **numeric term ID from this site's seed** (2 Breakfast & Brunch,
  3 Main Dishes, 4 Desserts & Baking, 5 Quick & Easy, 6 Kitchen Tips,
  7 Cooking Guides).
- Recipes are **flat** contract §2.3: `type:"recipe"` + `ingredients[]`,
  `steps[]`, `prep`, `cook`, `servings`, `course`. The plugin turns these into
  the `_wpap_recipe_*` meta → visible recipe card + schema.org/Recipe JSON-LD.
  A nested `recipe{}` object is build-final only and would be ignored here.
- `content` is intro prose only — ingredient/step lists are NOT repeated in
  the body (the plugin renders them from the flat fields).
- `metaDescription` is camelCase, 150-160 chars. `hook` = Facebook caption,
  `comment: "{{link}}"` = link as first comment, per network practice.
- Images: `images/<slug>.jpg`, own AI-generated photography (no stock, per the
  network image rule), referenced by relative path for Bulk-ZIP import.

## To publish (owner-gated, at launch)

Make a Bulk-ZIP with `posts.json` at the root and `images/` alongside, then
upload via wp-admin → Automation Hamri → Bulk-ZIP. Do **not** place this
folder under `data/bundles/` — that directory auto-imports on container boot.

Local proof (2026-11-06, QA stack on port 8098): all 10 items were imported
through the same `wpap_publish_article` path the seed uses for bundles; the
shepherd's pie page rendered the recipe card (prep/cook/total, ingredients,
method) + Recipe JSON-LD with `recipeIngredient`; the knife guide correctly
had no recipe card. Test posts and attachments were then permanently deleted
and the stack re-verified at 0 posts / 0 attachments.

## The menu

| # | Title | Type | Category | Content words |
|---|-------|------|----------|---------------|
| 1 | Fluffy Buttermilk Pancakes with Maple Butter | recipe | 2 Breakfast & Brunch | 536 |
| 2 | Spinach and Feta Breakfast Egg Muffins | recipe | 2 Breakfast & Brunch | 520 |
| 3 | Classic Shepherd's Pie with Cheesy Mash | recipe | 3 Main Dishes | 513 |
| 4 | Weeknight Chicken Tikka Masala | recipe | 3 Main Dishes | 517 |
| 5 | Creamy Garlic Mushroom Pasta | recipe | 3 Main Dishes | 470 |
| 6 | Sticky Toffee Pudding with Warm Toffee Sauce | recipe | 4 Desserts & Baking | 504 |
| 7 | Lemon Drizzle Loaf Cake | recipe | 4 Desserts & Baking | 537 |
| 8 | One-Pan Sausage and White Bean Bake | recipe | 5 Quick & Easy | 499 |
| 9 | How to Sharpen a Kitchen Knife on a Whetstone | guide | 7 Cooking Guides | 802 |
| 10 | How to Store Fresh Herbs So They Last Longer | guide | 6 Kitchen Tips | 583 |
