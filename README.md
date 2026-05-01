# WP SiteBuilderOne FAQ

WP SB1 FAQ is a zero-dependency WordPress plugin that registers `faq` and `howto` custom post types. FAQs include a plain-text answer field, optional linking to a `service` post type (from the companion WP SB1 Services plugin), a shortcode for display, FAQPage JSON-LD schema output, and REST API exposure of meta fields. HowTos include description, total time, supplies, ordered steps, tag filtering, HowTo JSON-LD schema output, and REST API exposure of meta fields.

## Shortcode Usage

FAQ and HowTo URL bases can be managed in **SiteBuilderOne → FAQ & HowTo**. If the SiteBuilderOne parent menu is not active, the page appears under **Settings → FAQ & HowTo**.

The `[sb1_faq]` shortcode renders a list of FAQs and injects inline FAQPage JSON-LD schema.

```
// All FAQs, default order
[sb1_faq]

// Show only 5 FAQs
[sb1_faq count="5"]

// Filter by service slug
[sb1_faq service="web-design"]

// Filter by service post ID
[sb1_faq service="42"]

// Custom ordering
[sb1_faq orderby="title" order="ASC"]

// Combined
[sb1_faq service="web-design" count="3" orderby="title" order="ASC"]
```

The `[sb1_howto]` shortcode renders a list of HowTos and injects inline HowTo JSON-LD schema.

```
// All HowTos, default order
[sb1_howto]

// Show only 5 HowTos
[sb1_howto count="5"]

// Filter by tag slug
[sb1_howto tag="smart-thermostats"]

// Filter by multiple tag slugs or IDs
[sb1_howto tags="smart-thermostats,42"]

// Custom ordering
[sb1_howto orderby="title" order="ASC"]

// Combined
[sb1_howto tag="smart-thermostats" count="3" orderby="title" order="ASC"]
```
