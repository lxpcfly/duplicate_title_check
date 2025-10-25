What this module does

Duplicate Title Check helps editors avoid confusing or SEO‑unfriendly duplicate page titles. When an editor saves a node whose Title matches an already published node (excluding the current one), the module:

Rebuilds the form and inserts a Drupal‑styled warning directly under the Title field, along with an acknowledgement checkbox (“Keep the same title anyway?”). The warning uses Drupal’s standard “messages—warning” pattern (with icon and “Warning” heading), rendered via a Twig template so it’s easy to theme.

Optionally highlights the Title input with a yellow border for extra visibility (shipped as a small admin CSS library you can override).

If the editor ticks the checkbox, the save proceeds with the duplicate title. If not, the form rebuilds and the editor can revise the Title or acknowledge.

Only published nodes are considered duplicates; unpublished content is ignored.

Key features

Published‑only duplicate check: avoids noise from drafts.

Per‑content‑type control: enable the check for specific bundles (or leave none selected to apply to all).

Inline, accessible UI: warning + checkbox render under the Title where editors are working; the Twig markup follows Drupal’s message semantics for accessibility.

Themable + skinnable: override the Twig template or swap the CSS for your admin theme; the default stylesheet adds a subtle amber/yellow border to the Title input when a duplicate is detected.

Safe and lean: one permission‑checked, LIMIT 1 entity query; excludes the current node; no information leakage about unpublished content.

How it works (editor flow)

Editor saves a node.

Module checks: Does any other published node have the exact same Title?

If no → save proceeds normally.

If yes → form rebuilds with:

a Warning block under Title, and

an acknowledgement checkbox (“Keep the same title anyway?”).

Editor either changes the Title, or ticks the checkbox and saves to proceed.

Configuration

Path: Configuration → Content authoring → Duplicate title check

Behavior:

Select the content types where the module should run.

If you leave all unchecked, the module treats it as “apply to all types”.

Permissions: uses the standard “Administer site configuration” to access the settings page.

Theming & styling

Warning block: Twig template duplicate-title-check-warning.html.twig (overridable in your theme). It outputs a standard Drupal warning message with a “Warning” heading and accessible roles/labels.

Title highlight: CSS class duplicate-title-warning is added to the Title input when a duplicate is detected; the module’s admin library ships a default yellow border style. Override or replace as needed.

Performance & security notes

Uses a single entityQuery('node') with status = 1 (published), nid != current, and range(0,1) to minimize load.

Queries run with accessCheck(TRUE) so editors never learn about content they cannot access.

Only checks for exact title equality (no fuzzy matching, transliteration, or synonyms).

Limitations / scope

Exact Title match only (case‑sensitive behavior is determined by your DB collation; most sites treat it case‑insensitively).

Doesn’t attempt to deduplicate aliases/URLs or other fields—this module is intentionally focused on titles.

Typical use cases

Announcements / news where editors might re‑use a previous headline by accident.

Large teams with shared editorial responsibilities where duplicate titles are common.

SEO hygiene to encourage unique, descriptive titles.

Files worth noting

Twig warning template: templates/duplicate-title-check-warning.html.twig — controls the look/markup of the warning.

Admin CSS: css/duplicate_title_check.admin.css — decorates the Title field with a yellow border on duplicate detection.
