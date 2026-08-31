# Deploying Sklentr to a fresh WordPress

## The order matters

```
1. Install WordPress
2. Activate the Sklentr theme      <-- must come before the import
3. Import the WXR file
4. (optional) Tools -> Sklentr Repair
```

**Do not import before activating the theme.** The WordPress importer validates
every record against the post types registered *at the moment it runs*. The
`skl_*` types live in this theme, so if a different theme is active the importer
rejects them:

```
Failed to import "GAinData": Invalid post type skl_work
```

A full export contains 82 such items — every service, portfolio piece,
technology, FAQ, stat, pricing tier and process step. They are dropped silently
apart from that one line scrolling past. Nothing later can recover them; you
have to re-import with the theme active.

Step 4 is a safety net, not a requirement — the same repair runs automatically
on theme activation and again on `import_end`.

## What breaks without this, and why

A WXR export contains posts, terms and postmeta. It contains **no options**.
Several things this theme relies on live in `wp_options`, so on a fresh install
they arrive missing or wrong. `inc/migration-safety.php` repairs each one:

| Symptom | Cause | Handled by |
|---|---|---|
| Every CPT item appears twice | Seeder guard flags (`sklentr_seeded_v18`, …) are options, so the seeders re-run over imported content | `sklentr_block_duplicate_seed()` |
| Every CPT item appears twice, the other way round | Theme activated first seeds legitimately, then the import adds its own copy; WP's importer matches on title+date+content so it does not spot them | `sklentr_resolve_seed_conflicts()` |
| Homepage shows the blog roll | `show_on_front` / `page_on_front` are options; "Home" only arrives with the import | `sklentr_restore_front_page()` |
| Every page 404s | No `.htaccess`, so WP falls back to PATHINFO permalinks (`/index.php/…`) | `sklentr_write_htaccess()` |
| CPT single pages 404 | Rewrite rules are an option | `sklentr_flush_rewrites()` |
| A page loses its design | Slug collided on import (`about-2`); this theme picks templates by slug (`page-about.php`) and enqueues JS via `is_page('about')` | `sklentr_normalize_slugs()` |
| Meta values doubled up | A re-run seeder appends rather than replaces | `sklentr_dedupe_postmeta()` |

## Note on `save_mod_rewrite_rules()`

`sklentr_write_htaccess()` writes the rewrite block itself rather than calling
`save_mod_rewrite_rules()` alone. That function is gated behind
`got_mod_rewrite()`, which reads the `$is_apache` global — false under WP-CLI and
any CLI/cron bootstrap. In those contexts it writes nothing and returns quietly,
which is exactly how a deploy ends up with every URL 404ing.

## Things the repair deliberately leaves alone

- WordPress' own boilerplate (`Hello world!`, `Sample Page`, the empty
  `Navigation` block). Harmless, and deleting user content automatically is not
  a theme's job.
- A slug already held by a **published** page. Only unpublished drafts get moved
  aside, which is what frees `privacy-policy` for the real imported page.
- Any `sklentr_settings` value that is already non-empty — seeding never
  overwrites an admin edit.

## Theme options are not in the export either

`sklentr_settings` holds the editable text for every section and is an option,
so it does **not** travel in a WXR. On a fresh install the seeders repopulate it
from the template defaults. If the source site had hand-edited copy, export that
option separately or re-enter it under the theme's options page.
