# Architecture

`kyc-refresh-case-router` is a local-first PHP + static Pages operator surface.

## Core pieces

1. `src/Data/sample_kyc_refresh_case_router.php`
   - synthetic KYC lanes, refresh queue artifacts, and verification gates
2. `src/Services/KycRefreshCaseRouterService.php`
   - aggregates summary counts and exposes the canonical payload
3. `src/Views/render.php`
   - renders the overview, KYC lane, refresh queue, review posture, verification, and docs routes
4. `public/index.php`
   - routes page and API requests through one local-first entry point
5. `plugin/kyc-refresh-case-router.php`
   - shortcode and REST primitive exposing one canonical KYC refresh snapshot
6. `scripts/prerender.php`
   - generates the static site bundle consumed by GitHub Pages

## Operating idea

The repo models a FinTech truth that often gets lost: beneficial-owner evidence, sanctions recency, and account release posture belong in one review lane, not in separate case queues, ticket systems, and spreadsheet exports.
