# WP SiteBuilderOne FAQ

WP SB1 FAQ is a zero-dependency WordPress plugin that registers a `faq` custom post type with a plain-text answer field, optional linking to a `service` post type (from the companion WP SB1 Services plugin), a shortcode for display, FAQPage JSON-LD schema output, and REST API exposure of meta fields.

## Shortcode Usage

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
