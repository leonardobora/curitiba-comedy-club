# Curitiba Comedy Club - Design System (v0)

## Visual direction

Creative direction for the first iteration:

- red carpet + streaming premium mood
- dark background surfaces
- strong contrast for readability
- high-energy red for CTA and accents
- clean, modern spacing rhythm

## Token strategy

All tokens are declared with the ccc-ui- prefix in:

- assets/css/ccc-ui-tokens.css

Core token groups:

- color
- typography
- spacing
- radius
- shadow

## CSS layers

### 1) Tokens

Defines custom properties only.

### 2) Base

Defines base reusable primitives:

- section wrappers
- containers
- headings
- text helpers

### 3) Components

Defines shortcode component styles:

- hero
- CTA block
- contact block

## Prefix rules

Use ccc-ui- in:

- CSS classes
- data attributes
- script handles
- shortcode classes and tags
- helper identifiers

## Astra and Elementor coexistence

- Components are class-scoped
- No hard reset on global theme styles
- Elementor sections can wrap shortcode output safely
- Astra typography and spacing can coexist while ccc-ui components keep visual identity
