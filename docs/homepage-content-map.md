# Happy Kiddos Homepage Content Contract

## Status

Approved for implementation except for content explicitly marked as pending client confirmation.

This document defines the public homepage structure, section ordering, navigation anchors, content ownership, visibility rules, and launch requirements.

The final website is one continuous server-rendered Blade homepage.

## 1. Scope

The homepage includes:

1. Header
2. Hero
3. Shop by Category
4. About
5. Happy Kiddos Advantages
6. Reviews
7. FAQ, when approved content exists
8. Contact CTA
9. Legal footer

The homepage does not include:

- Native shopping cart
- Native checkout
- Payment gateway
- Inventory management
- Customer accounts
- Client-side routing
- React or another JavaScript SPA framework

Shopee, Lazada, Facebook, Instagram, and Shop Now remain external destinations.

## 2. Brand Naming Contract

The repository and project use the spelling:

```text
Happy Kiddos
```

Some generated design references use:

```text
Happy Kiddos
```

Until the client confirms otherwise:

- Use `Happy Kiddos` as the working brand name.
- Store the displayed name in the global `brand_name` setting.
- Do not hard-code the brand name throughout Blade components.
- Confirm that the final logo asset uses the client's official spelling before launch.

## 3. Homepage Section Order

| Sort order | Section key  | HTML anchor | Default visibility | Required for launch |
| ---------: | ------------ | ----------- | ------------------ | ------------------- |
|         10 | `hero`       | None        | Visible            | Yes                 |
|         20 | `categories` | `shop`      | Visible            | Yes                 |
|         30 | `about`      | `about`     | Visible            | Yes                 |
|         40 | `advantages` | `why-us`    | Visible            | Yes                 |
|         50 | `reviews`    | `reviews`   | Visible            | Yes                 |
|         60 | `faq`        | `faq`       | Hidden             | No                  |
|         70 | `contact`    | `contact`   | Visible            | Yes                 |

The section key is the internal CMS identifier.

The HTML anchor is the public fragment identifier used by navigation.

Section keys and anchor identifiers must remain unique.

## 4. Header Navigation Contract

| Label    | Destination               | Visibility rule                             |
| -------- | ------------------------- | ------------------------------------------- |
| Shop     | `/#shop`                  | Show when categories are visible            |
| About    | `/#about`                 | Show when about is visible                  |
| Why Us   | `/#why-us`                | Show when advantages are visible            |
| Reviews  | `/#reviews`               | Show when reviews are visible               |
| FAQ      | `/#faq`                   | Show only when FAQ is visible and populated |
| Contact  | `/#contact`               | Show when contact is visible                |
| Shop Now | Global `primary_shop_url` | Show only when a valid URL exists           |

The FAQ navigation item must not be rendered while the FAQ section is hidden or empty.

No navigation or CTA may use:

```text
href="#"
javascript:void(0)
```

Unavailable external links must be stored as `null` and omitted from the rendered page.

## 5. Global Settings Contract

The following values belong to site settings because they are shared across multiple sections.

### Branding

| Key             | Type       | Required |
| --------------- | ---------- | -------- |
| `brand_name`    | Plain text | Yes      |
| `brand_tagline` | Plain text | No       |
| `logo_path`     | Image path | Yes      |
| `favicon_path`  | Image path | Yes      |

### Marketplace and Social Links

| Key                | Type      | Required |
| ------------------ | --------- | -------- |
| `primary_shop_url` | HTTPS URL | Yes      |
| `shopee_url`       | HTTPS URL | No       |
| `lazada_url`       | HTTPS URL | No       |
| `facebook_url`     | HTTPS URL | No       |
| `instagram_url`    | HTTPS URL | No       |

### Contact Details

| Key               | Type          | Required |
| ----------------- | ------------- | -------- |
| `contact_email`   | Email address | Yes      |
| `contact_phone`   | Phone number  | Yes      |
| `contact_viber`   | Phone number  | No       |
| `contact_address` | Plain text    | Yes      |
| `contact_map_url` | HTTPS URL     | No       |

### SEO and Legal

| Key                  | Type                  | Required |
| -------------------- | --------------------- | -------- |
| `meta_title`         | Plain text            | Yes      |
| `meta_description`   | Plain text            | Yes      |
| `privacy_policy_url` | Internal or HTTPS URL | No       |
| `terms_url`          | Internal or HTTPS URL | No       |
| `copyright_text`     | Plain text            | No       |

The copyright year should be generated dynamically by the application.

## 6. Header Content

The header contains:

- Approved Happy Kiddos logo
- Homepage anchor navigation
- Primary Shop Now action
- Responsive mobile menu

The header is not a separate homepage section record.

Its links are generated from:

- Section visibility
- Section anchors
- Global site settings

## 7. Hero Section

### Working Content

**Eyebrow**

```text
Premium Montessori furniture and materials for curious little minds
```

**Headline**

```text
Beautiful spaces where little ones grow.
```

**Description**

```text
Thoughtfully designed Montessori furniture and materials that inspire independence, creativity, and joyful learning for homes, schools, and learning centers.
```

**Rating**

```text
4.9
```

**Trust Statement**

```text
Loved by many Filipino families and school owners since 2016!
```

### Content Fields

```text
eyebrow
headline
description
image_path
image_alt
rating_value
rating_statement
```

### External Actions

```text
Shopee
Lazada
Facebook
Instagram
```

The destination URLs come from global settings.

The hero contains the page's only `h1`.

## 8. Categories Section

### Section Content

**Eyebrow**

```text
Shop by Category
```

**Heading**

```text
Everything for a purposeful play space
```

### Required Category Order

| Order | Title             | Description                                           |
| ----: | ----------------- | ----------------------------------------------------- |
|    10 | Tables & Chairs   | Right-sized seating for focused little learners.      |
|    20 | Bookshelves       | Front-facing shelves that invite independent reading. |
|    30 | Toy Organizers    | Tidy, reachable storage that teaches order.           |
|    40 | Learning Towers   | Safe kitchen helpers for hands-on discovery.          |
|    50 | Play Gyms         | Indoor climbers that build strength and courage.      |
|    60 | Montessori Toys   | Natural, purposeful play for growing minds.           |
|    70 | STEM Toys         | Gears, builds and puzzles that spark curiosity.       |
|    80 | Storage and Trays | Beautiful order for shelves and activity work.        |

### Category Fields

```text
title
description
image_path
image_alt
external_url
sort_order
```

`external_url` is optional.

A category card without an approved destination remains informational and must not behave like a fake link.

## 9. About Section

### Eyebrow

```text
About Us
```

### Heading

```text
Happy Kiddos was founded in 2016, inspired by a personal experience as a parent.
```

### Paragraph 1

```text
When our first child was born, we searched for child-sized bookshelves, toy organizers, and tables and chairs, but they were difficult to find, expensive to customize, and inconvenient to transport. As a former preschool teacher, we saw an opportunity to create furniture based on the Montessori philosophy—safe, child-sized, functional, and designed to encourage independence.
```

### Paragraph 2

```text
Over the years, our two children became our biggest product testers and inspiration. Their everyday use helped us refine every design to better meet the needs of growing families and classrooms.
```

### Paragraph 3

```text
Today, our mission is simple: to make quality, affordable Montessori furniture and educational materials accessible to every Filipino home, preschool, and learning center. We continue to grow toward becoming a trusted one-stop destination for Montessori-inspired learning spaces.
```

### Content Fields

```text
eyebrow
heading
paragraphs
image_path
image_alt
```

## 10. Advantages Section

### Eyebrow

```text
Why Parents and Schools Choose Us
```

### Heading

```text
The Happy Kiddos Advantage
```

### Required Items

| Order | Icon key   | Title                           | Description                                                                                    |
| ----: | ---------- | ------------------------------- | ---------------------------------------------------------------------------------------------- |
|    10 | `sparkles` | Pioneer in Montessori Furniture | The first to offer Montessori-inspired furniture in the Philippine market.                     |
|    20 | `leaf`     | Natural & non-toxic             | Solid, sustainably sourced wood with child-safe, non-toxic finishes and smooth, rounded edges. |
|    30 | `blocks`   | Montessori-aligned              | Designed to be child-sized and accessible, encouraging independence and focused play.          |
|    40 | `shield`   | Built to last                   | Sturdy construction tested for busy homes and classrooms, made to grow with your child.        |
|    50 | `truck`    | Nationwide delivery             | Fast, careful shipping across the Philippines via Shopee, Lazada, and approved couriers.       |

Icons must use controlled application identifiers.

Do not allow arbitrary SVG, JavaScript, or HTML to be entered through the CMS.

## 11. Reviews Section

### Eyebrow

```text
Real Reviews From Real People
```

### Heading

```text
Over 20,000 positive reviews!
```

### Review Fields

```text
quote
customer_name
rating
source
source_url
sort_order
is_visible
```

### Content Approval Rule

The final combined design contains four review cards.

Before reviews are seeded or published, confirm:

- Exact review wording
- Customer or marketplace username
- Permission to display the review
- Review source
- Optional source URL
- Rating value

Only approved reviews may be visible.

Review content must be rendered as escaped plain text.

## 12. FAQ Section

The FAQ section is supported by the content model but is hidden by default.

### Initial State

```text
is_visible = false
items = []
```

### FAQ Fields

```text
question
answer
sort_order
is_visible
```

The FAQ navigation item must be omitted until:

1. At least one approved FAQ exists.
2. The FAQ section is visible.
3. The FAQ section is rendered on the page.

FAQ answers are plain text unless a separate sanitization policy is approved.

## 13. Contact Section

### Heading

```text
Let's build a happier space for your kiddos
```

### Supporting Text

```text
Shopping for home, furnishing a classroom, or planning a learning center? Reach out—we'd love to help you choose the right pieces.
```

### Right-Panel Heading

```text
Shop and follow us
```

### Content Ownership

The section record owns:

```text
heading
description
links_panel_heading
```

Global site settings own:

```text
contact_email
contact_phone
contact_viber
contact_address
contact_map_url
shopee_url
lazada_url
facebook_url
instagram_url
```

The contact details shown in the design remain pending client confirmation before production seeding.

## 14. Footer Contract

The legal footer contains:

- Happy Kiddos logo
- Dynamic copyright year
- Brand name
- Privacy Policy link, when configured
- Terms of Service link, when configured

Missing legal URLs must cause their corresponding links to be omitted.

## 15. Content Rendering Rules

- CMS text is escaped by default.
- Plain-text fields must not be rendered using unescaped Blade output.
- Empty optional fields are omitted cleanly.
- No placeholder `#` URLs are rendered.
- External links use `rel="noopener noreferrer"`.
- The page contains one `h1`.
- Each major content section uses an `h2`.
- Card headings use `h3` where appropriate.
- Heading levels must not be skipped arbitrarily.
- Every meaningful image has approved alternative text.
- Decorative images use an empty alternative text value.
- Section anchor IDs remain stable after launch.

## 16. Content Approval Checklist

The following items require explicit client confirmation before launch:

- Official spelling: Happy Kiddos or Happy Kiddos
- Final logo
- Final hero and category images
- Marketplace URLs
- Social URLs
- Primary Shop Now destination
- Contact phone
- Viber number
- Physical address
- Review wording and usernames
- FAQ questions and answers
- Privacy Policy URL
- Terms of Service URL

## 17. Definition of Done

This contract is complete when:

- Section keys and ordering are agreed.
- Navigation labels and anchors are agreed.
- FAQ visibility behavior is documented.
- Global settings and section-owned content are separated.
- No fake or placeholder links are permitted.
- Brand spelling inconsistency is recorded.
- Review approval requirements are recorded.
- Production asset requirements are documented.
- Later implementation phases can proceed without redefining the homepage structure.
