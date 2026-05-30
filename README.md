# KYC Refresh Case Router

WordPress and FinTech control plane for KYC refresh routing, beneficial-owner evidence, review packets, and account-safe remediation posture.

## Why this exists

- FinTech review teams usually split beneficial-owner refreshes, sanctions recency, document expiry checks, and unblock memos across too many tools.
- Risk, treasury, onboarding, and payout teams need one view of which KYC lanes are safe, which packets are stale, and which refresh artifacts still block release.
- Account-safe decisioning breaks when the queue, the review packet, and the final unblock memo all say different things.

## Why this matters (KG Embedded tie-back)

This repo demonstrates the KYC-refresh primitive for Kinetic Gain Embedded: beneficial-owner evidence, sanctions recency, unblock posture, and review-safe routing exposed through one operator surface. In a real embedded setting, the same primitive lets FinTech teams keep refresh workflow, payout decisions, and identity-risk review aligned without shipping account changes blindly.

## Routes

- `/`
- `/kyc-lane`
- `/refresh-queue`
- `/review-posture`
- `/verification`
- `/docs`

## API

- `/api/dashboard/summary`
- `/api/kyc-lane`
- `/api/refresh-queue`
- `/api/verification`
- `/api/sample`

## Screenshots

![Overview](./screenshots/01-overview-proof.png)
![KYC lane](./screenshots/02-kyc-lane-proof.png)
![Refresh queue](./screenshots/03-refresh-queue-proof.png)
![Verification](./screenshots/04-verification-proof.png)

## Local development

```powershell
cd kyc-refresh-case-router
php -S 127.0.0.1:5442 .\router.php
```

Open:
- [http://127.0.0.1:5442/](http://127.0.0.1:5442/)
- [http://127.0.0.1:5442/kyc-lane](http://127.0.0.1:5442/kyc-lane)
- [http://127.0.0.1:5442/refresh-queue](http://127.0.0.1:5442/refresh-queue)
- [http://127.0.0.1:5442/review-posture](http://127.0.0.1:5442/review-posture)
- [http://127.0.0.1:5442/verification](http://127.0.0.1:5442/verification)

## Validation

- `php -l public\index.php`
- `php -l src\Services\KycRefreshCaseRouterService.php`
- `php -l src\Views\render.php`
- `php -l plugin\kyc-refresh-case-router.php`
- `php scripts\run_demo.php`
- `php scripts\prerender.php`
- `powershell -ExecutionPolicy Bypass -File .\scripts\smoke_check.ps1`
- `powershell -ExecutionPolicy Bypass -File .\scripts\render_readme_assets.ps1`

## Production status

| Aspect | Status |
|--------|--------|
| License | [AGPL-3.0-or-later](./LICENSE) |
| Security | [SECURITY.md](./SECURITY.md) |
| Deploy | Static prerender -> **https://kyc.kineticgain.com/** |
| WordPress primitive | KYC refresh snapshot shortcode + REST route |

## Docs

- [Architecture](./docs/architecture.md)
- [Origin](./docs/ORIGIN.md)
- [Kinetic Gain Embedded tie-back](./docs/KINETIC_GAIN_EMBEDDED.md)
- [Changelog](./CHANGELOG.md)

## Part of the Kinetic Gain Suite

Operator surface in the [Kinetic Gain Suite](https://suite.kineticgain.com/) — a portfolio of buyer-readable control planes spanning compliance evidence, FinTech risk, property operations, identity posture, and operator workflows. Apex: [kineticgain.com](https://kineticgain.com/).
