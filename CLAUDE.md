# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Plugin Overview

WP SB1 FAQ is a zero-dependency WordPress plugin that registers a `faq` custom post type with a plain-text answer field, optional linking to a `service` post type (from the companion WP SB1 Services plugin), a shortcode for display, FAQPage JSON-LD schema output, and REST API exposure of meta fields.

## Development Environment

This plugin runs inside a Local by Flywheel site at:
`/Users/anton7/Local Sites/wp-sitebuilderone-lite/app/public/wp-content/plugins/wp-sb1-faq/`

There is no build step, package manager, or test suite — all PHP is plain WordPress procedural/OOP code loaded via `require_once`.

To test changes, reload the relevant WordPress admin or frontend page in the browser. Flush rewrite rules after any CPT registration change by visiting **Settings → Permalinks** and saving, or calling `flush_rewrite_rules()`.

## Architecture

All functionality is split into static-method classes, each in `includes/`. The main plugin file (`wp-sb1-faq.php`) registers every WordPress hook; the classes themselves contain no hook calls in their constructors.

| Class | File | Responsibility |
|---|---|---|
| `SB1_FAQ_CPT` | `includes/class-cpt.php` | Registers the `faq` post type (`rest_base: faqs`, `show_in_rest: true`) |
| `SB1_FAQ_Meta_Boxes` | `includes/class-meta-boxes.php` | Admin meta box for `_sb1_faq_answer` (textarea) and `_sb1_faq_related_service` (select, populated from `service` CPT if present) |
| `SB1_FAQ_Rest_Fields` | `includes/class-rest-fields.php` | Exposes `_sb1_faq_answer` and `_sb1_faq_related_service` via `register_post_meta` with `show_in_rest: true` |
| `SB1_FAQ_Shortcode` | `includes/class-shortcode.php` | `[sb1_faq]` shortcode — supports `count`, `service` (ID or slug), `orderby`, `order` atts; includes schema output inline |
| `SB1_FAQ_Schema` | `includes/class-schema.php` | Builds and outputs FAQPage JSON-LD; `build_schema()` is a shared static method called by both the shortcode and the single-post `wp_head` hook |

## Key Meta Keys

- `_sb1_faq_answer` — plain-text answer string
- `_sb1_faq_related_service` — integer post ID of the linked `service` post (optional)

## Template Overrides

The shortcode template at `templates/faq-list.php` can be overridden by a theme by placing a file at `{active-theme}/sb1-faq/faq-list.php`. The override is resolved in `SB1_FAQ_Shortcode::locate_template()` via `locate_template()`.

## Coding Conventions

- PHP text domain: `wp-sb1-faq`
- Plugin constants: `SB1_FAQ_VERSION`, `SB1_FAQ_DIR`, `SB1_FAQ_URL`
- All classes are prefixed `SB1_FAQ_`; all meta keys are prefixed `_sb1_faq_`
- No external dependencies; no composer, no npm
- All user-facing strings pass through `__()` / `esc_html_e()` with the `wp-sb1-faq` text domain
