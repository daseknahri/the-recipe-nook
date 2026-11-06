#!/usr/bin/env python3
"""The Recipe Nook brand pack: icon set, logo lockups, OG card +
theme assets from generated art.

Type is composited here (Anton), never baked by an image model. Run from the repo root:
    python3 brand/brand_compose.py
Reads the two raw generations in this folder and writes the pack here, the child theme's
assets/brand/ set, and content/images/. Brand decided 2026-11-06: The Recipe Nook
(owner delegated branding; picked from his shortlist). To refresh the pack, replace the
raw art, keep the lockup text below in sync with content/site-profile.json, and re-run."""
from PIL import Image, ImageDraw, ImageFont, ImageOps
import os

BASE = os.path.dirname(os.path.abspath(__file__))
REPO = os.path.dirname(BASE)
FONT = os.path.expanduser('~/.fonts/Anton-Regular.ttf')
MARK = f'{BASE}/media-generation-recipenook-mark-0-ded535e9-360b-401f-8ea4-e870726acf46.webp'
HERO = f'{BASE}/media-generation-recipenook-hero-0-f7eca9fb-42ec-4a39-8079-d7a6d2d4608e.webp'
THEME_BRAND = f'{REPO}/wp-content/themes/recipes-site/assets/brand'
CONTENT_IMG = f'{REPO}/content/images'
os.makedirs(THEME_BRAND, exist_ok=True)
os.makedirs(CONTENT_IMG, exist_ok=True)

CREAM = (250, 245, 236)
INK = (43, 33, 24)
TERRA = (196, 85, 43)
HERB = (62, 107, 52)
GOLD = (217, 164, 65)

# --- icon: cut the terracotta badge out of the square art ---
art = Image.open(MARK).convert('RGB')
S = min(art.size)
art = art.crop(((art.width - S) // 2, (art.height - S) // 2, (art.width + S) // 2, (art.height + S) // 2)).resize((1024, 1024), Image.LANCZOS)
# The badge spans 132-1476px of the 1600px art (84-945 at 1024 scale), centred: crop to it, then circular-mask.
art = art.crop((84, 84, 945, 945)).resize((1024, 1024), Image.LANCZOS)
mask = Image.new('L', (1024, 1024), 0)
md = ImageDraw.Draw(mask)
md.ellipse([4, 4, 1020, 1020], fill=255)
icon = Image.new('RGBA', (1024, 1024), (0, 0, 0, 0))
icon.paste(art, (0, 0), mask)
icon.resize((512, 512), Image.LANCZOS).save(f'{BASE}/icon-512.png')
icon.resize((180, 180), Image.LANCZOS).save(f'{BASE}/apple-touch-180.png')
icon.resize((32, 32), Image.LANCZOS).save(f'{BASE}/favicon-32.png')
print('icons done')

# --- logo lockups (horizontal: badge + wordmark) ---
def lockup(col_main, col_accent, path):
    H = 300
    emb = icon.resize((H, H), Image.LANCZOS)
    f_title = ImageFont.truetype(FONT, 104)
    f_tag = ImageFont.truetype(FONT, 38)
    tmp = ImageDraw.Draw(Image.new('RGBA', (10, 10)))
    parts = [('THE RECIPE ', col_main), ('NOOK', col_accent)]
    tw = sum(tmp.textlength(t, font=f_title) for t, _c in parts)
    tag = 'R E C I P E S   ·   T I P S   ·   G U I D E S'
    gw = tmp.textlength(tag, font=f_tag)
    W = H + 40 + int(max(tw, gw)) + 20
    img = Image.new('RGBA', (W, H), (0, 0, 0, 0))
    img.paste(emb, (0, 0), emb)
    d = ImageDraw.Draw(img)
    x = H + 40
    y = 62
    for t, c in parts:
        d.text((x, y), t, font=f_title, fill=c)
        x += tmp.textlength(t, font=f_title)
    d.text((H + 44, y + 140), tag, font=f_tag, fill=col_main)
    img.save(path)
    print('lockup', path)

lockup(INK, TERRA, f'{BASE}/logo.png')        # for light backgrounds
lockup(CREAM, GOLD, f'{BASE}/logo-light.png')  # for dark backgrounds

# --- OG / social card 1200x630 from the hero ---
hero = Image.open(HERO).convert('RGB')
og = ImageOps.fit(hero, (1200, 630), Image.LANCZOS)
grad = Image.new('L', (1, 630))
grad.putdata([int(175 * max(0.0, (y / 630 - 0.30) / 0.70)) for y in range(630)])
og = Image.composite(Image.new('RGB', (1200, 630), INK), og, grad.resize((1200, 630)))
d = ImageDraw.Draw(og)
emb = icon.resize((150, 150), Image.LANCZOS)
og.paste(emb, (48, 630 - 150 - 42), emb)
f = ImageFont.truetype(FONT, 84)
x = 224
d.text((x, 630 - 172), 'THE RECIPE ', font=f, fill=CREAM)
x += d.textlength('THE RECIPE ', font=f)
d.text((x, 630 - 172), 'NOOK', font=f, fill=GOLD)
og.save(f'{BASE}/og.jpg', quality=88)
print('og done')

# --- hero master for brand/ ---
ImageOps.fit(hero, (1920, 1080), Image.LANCZOS).save(f'{BASE}/hero-1920.jpg', quality=88)

# --- theme assets ---
icon.resize((512, 512), Image.LANCZOS).save(f'{THEME_BRAND}/recipes-site-icon-512.png')
icon.resize((192, 192), Image.LANCZOS).save(f'{THEME_BRAND}/icon-192.webp', 'WEBP', quality=90)
Image.open(f'{BASE}/logo.png').save(f'{THEME_BRAND}/recipes-site-logo.png')
Image.open(f'{BASE}/logo.png').save(f'{THEME_BRAND}/recipes-site-logo.webp', 'WEBP', quality=92)
Image.open(f'{BASE}/logo-light.png').save(f'{THEME_BRAND}/recipes-site-logo-light.webp', 'WEBP', quality=92)
Image.open(f'{BASE}/og.jpg').save(f'{THEME_BRAND}/og.jpg', quality=88)
Image.open(f'{BASE}/apple-touch-180.png').save(f'{THEME_BRAND}/apple-touch-180.png')
Image.open(f'{BASE}/favicon-32.png').save(f'{THEME_BRAND}/favicon-32.png')
for w in (640, 768, 960, 1280):
    ImageOps.fit(hero, (w, int(w * 9 / 16)), Image.LANCZOS).save(f'{THEME_BRAND}/hero-{w}.webp', 'WEBP', quality=86)
print('theme assets done')

# --- content/images (seed reads these names from the profile assets) ---
icon.resize((512, 512), Image.LANCZOS).save(f'{CONTENT_IMG}/recipes-site-icon.png')
Image.open(f'{BASE}/logo.png').save(f'{CONTENT_IMG}/recipes-site-logo.png')
Image.open(f'{BASE}/og.jpg').save(f'{CONTENT_IMG}/recipes-site-og.jpg', quality=88)
ImageOps.fit(hero, (1600, 900), Image.LANCZOS).save(f'{CONTENT_IMG}/recipes-site-hero.jpg', quality=88)
Image.open(f'{BASE}/favicon-32.png').save(f'{CONTENT_IMG}/favicon-32.png')
Image.open(f'{BASE}/apple-touch-180.png').save(f'{CONTENT_IMG}/apple-touch-180.png')

# --- theme screenshot (1200x900) ---
ImageOps.fit(hero, (1200, 900), Image.LANCZOS).save(f'{REPO}/wp-content/themes/recipes-site/screenshot.jpg', quality=85)
print('all done')
