# Happy Kiddos Homepage Asset Contract

## Status

Production assets are pending client approval.

The supplied screenshots are visual references. They must not be embedded directly into the website or sliced into production images.

## 1. Source Reference Map

| Source reference              | Purpose                                   |
| ----------------------------- | ----------------------------------------- |
| Final combined homepage image | Primary single-page composition reference |
| Hero screenshot               | Hero layout and classroom image reference |
| Category screenshot 1         | Category cards, first row                 |
| Category screenshot 2         | Category cards, second row                |
| About screenshot              | About section                             |
| Advantages screenshot 1       | Primary advantage cards                   |
| Advantages screenshot 2       | Nationwide delivery continuation          |
| Reviews screenshot            | Final review layout reference             |
| Alternate reviews screenshot  | Alternate review-content reference        |
| Contact screenshot            | Contact CTA and marketplace panel         |

The final combined homepage image takes precedence when individual screenshots conflict with it.

## 2. Production Asset Directories

```text
public/images/brand/
├── happy-kiddos-logo.webp
└── favicon.webp

database/seeders/assets/homepage/
├── hero.webp
├── about.webp
└── categories/
    ├── tables-and-chairs.webp
    ├── bookshelves.webp
    ├── toy-organizers.webp
    ├── learning-towers.webp
    ├── play-gyms.webp
    ├── montessori-toys.webp
    ├── stem-toys.webp
    └── storage-and-trays.webp
```

The seeder asset directory contains default content that can later be copied into the configured public storage disk.

## 3. Required Asset Inventory

| Asset             | Required | Preferred format        | Recommended source dimensions | Display ratio     |
| ----------------- | -------- | ----------------------- | ----------------------------: | ----------------- |
| Brand logo        | Yes      | SVG or transparent WebP |           512 × 512 or larger | Preserve original |
| Favicon           | Yes      | WebP or PNG             |                     512 × 512 | 1:1               |
| Hero image        | Yes      | WebP                    |         1600 × 1200 or larger | 4:3               |
| About image       | Yes      | WebP                    |         1400 × 1050 or larger | 4:3               |
| Tables & Chairs   | Yes      | WebP                    |           800 × 600 or larger | 4:3               |
| Bookshelves       | Yes      | WebP                    |           800 × 600 or larger | 4:3               |
| Toy Organizers    | Yes      | WebP                    |           800 × 600 or larger | 4:3               |
| Learning Towers   | Yes      | WebP                    |           800 × 600 or larger | 4:3               |
| Play Gyms         | Yes      | WebP                    |           800 × 600 or larger | 4:3               |
| Montessori Toys   | Yes      | WebP                    |           800 × 600 or larger | 4:3               |
| STEM Toys         | Yes      | WebP                    |           800 × 600 or larger | 4:3               |
| Storage and Trays | Yes      | WebP                    |           800 × 600 or larger | 4:3               |

Original photography is preferred over images cropped from screenshots.

## 4. File Naming Convention

Use lowercase kebab-case filenames.

Correct:

```text
learning-towers.webp
tables-and-chairs.webp
happy-kiddos-logo.webp
```

Incorrect:

```text
Learning Towers Final 2.webp
IMG_1928.webp
logo-new-final-final.webp
```

Do not include spaces, temporary labels, or version numbers in production filenames.

## 5. Alternative Text Contract

### Logo

```text
Happy Kiddos
```

When the logo is immediately beside visible brand text and would be redundant, use an empty alt value.

### Hero Image

```text
Montessori classroom with child-sized wooden tables, chairs, shelves, and learning materials
```

### About Image

```text
Child using Montessori furniture and toys in a prepared learning space
```

### Category Images

```text
Child-sized wooden table and chairs
Front-facing wooden children's bookshelves
Child-accessible wooden toy organizer
Wooden learning tower for kitchen activities
Indoor wooden climbing gym
Natural wooden Montessori toys
Colorful STEM gears and activity toys
Wooden storage trays on open shelving
```

Alternative text must describe the meaningful visual content rather than repeat surrounding headings.

## 6. Image Handling Requirements

- Preserve original aspect ratio during processing.
- Crop deliberately for the approved display ratio.
- Do not stretch images.
- Remove unnecessary metadata where practical.
- Use explicit width and height attributes when rendering.
- Generate thumbnails or responsive variants when later performance testing shows a benefit.
- Load the hero image eagerly.
- Give the hero image high fetch priority.
- Lazy-load below-the-fold images.
- Avoid embedding images as base64 strings in Blade or CSS.
- Store database file paths rather than binary image contents.
- Keep filenames randomized for future administrator uploads.
- Reject executable or non-image uploads.

## 7. Initial Compression Targets

These are starting targets, not hard limits.

| Asset type     |  Suggested target |
| -------------- | ----------------: |
| Logo           |      Under 100 KB |
| Favicon        |       Under 50 KB |
| Hero image     |      Under 500 KB |
| About image    |      Under 400 KB |
| Category image | Under 250 KB each |

Visual quality must be checked after compression.

## 8. Asset Ownership and Approval

Before production use, confirm:

- The client owns or is licensed to use every image.
- Customer photographs have any required consent.
- Marketplace review screenshots are not used as image assets.
- The logo is the official client-provided version.
- The official brand spelling is confirmed.
- No generated placeholder image remains in production.

## 9. Pending Client Assets

The following remain pending until original files are supplied:

- Official logo source
- Transparent logo variant
- Favicon
- Hero photograph
- About photograph
- Eight category photographs
- Approved marketplace and social icons, when custom brand assets are required

Standard interface icons should come from a controlled application icon set rather than administrator-uploaded SVG markup.

## 10. Definition of Done

The asset contract is complete when:

- Every visual section has an identified production asset.
- Every meaningful image has proposed alternative text.
- Production paths and naming rules are fixed.
- Screenshot references are separated from production files.
- Missing original assets are clearly marked.
- File ownership and client approval requirements are recorded.
