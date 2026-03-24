# Curitiba Comedy Club - Architecture

## Current repository strategy

This repository intentionally keeps plugins in the root during the bootstrap phase:

- ccc-eventos-standapp/
- ccc-ui-kit/

A future migration to a wp-content/plugins mirror can be done later, with low risk, after the institutional layer is stable.

## Plugin boundaries

### ccc-eventos-standapp

Owns all agenda-specific behavior:

- Standapp API integration
- event normalization
- event filters and badges
- agenda rendering shortcode
- schedule-specific business logic

### ccc-ui-kit

Owns all institutional and visual system behavior:

- design system tokens and base styles
- reusable UI components
- institutional shortcodes
- lightweight JS for UI helpers

## Compatibility goals

- Keep WordPress native APIs
- Keep Astra as lightweight base theme
- Keep Elementor for macro layout and shortcode placement
- Avoid heavy dependencies and frameworks

## Bootstrap design decisions

- No Composer requirement
- One main plugin file plus small include classes
- Static asset files (no inline CSS/JS)
- Prefix all assets, classes, tags and handles with ccc-ui-

## First shortcode scope

Initial shortcode set in ccc-ui-kit:

- [ccc_page_hero]
- [ccc_cta_ingressos]
- [ccc_contact_section]

Additional shortcodes can be added after page-level validation.
