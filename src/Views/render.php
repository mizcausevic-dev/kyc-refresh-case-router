<?php

declare(strict_types=1);

namespace KycRefreshCaseRouter\Views;

use KycRefreshCaseRouter\Services\KycRefreshCaseRouterService;

function status_class(string $status): string
{
    return match ($status) {
        'blocked', 'critical', 'needs-refresh' => 'critical',
        'watch' => 'watch',
        default => 'good',
    };
}

function shell(string $active, string $title, string $eyebrow, string $hero, string $intro, string $body, array $rightCards): string
{
    $service = new KycRefreshCaseRouterService();
    $summary = $service->summary();
    $operatorPosture = htmlspecialchars((string) $summary['operatorPosture'], ENT_QUOTES);
    $leadRecommendation = htmlspecialchars((string) $summary['leadRecommendation'], ENT_QUOTES);
    $rightCardsHtml = render_side_cards($rightCards);
    $nav = render_nav($active);

    $safeTitle = htmlspecialchars($title, ENT_QUOTES);
    $safeEyebrow = htmlspecialchars($eyebrow, ENT_QUOTES);
    $safeHero = htmlspecialchars($hero, ENT_QUOTES);
    $safeIntro = htmlspecialchars($intro, ENT_QUOTES);

    return <<<HTML
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{$safeTitle}</title>
  <style>
    :root{
      --panel:#0b1220; --line:rgba(120,255,170,.18); --line2:rgba(120,255,170,.10);
      --text:#e9f3ff; --muted:rgba(233,243,255,.72); --muted2:rgba(233,243,255,.55);
      --green:#37ff8b; --cyan:#19c7ff; --warn:#ffcc66; --bad:#ff5c7a; --plum:#b88cff;
      --shadow:0 18px 60px rgba(0,0,0,.55);
      --mono:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
      --sans:ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
    }
    *{box-sizing:border-box} html,body{height:100%}
    body{
      margin:0; font-family:var(--sans); color:var(--text);
      background:
        radial-gradient(1200px 600px at 20% -10%, rgba(55,255,139,.18), transparent 60%),
        radial-gradient(900px 520px at 90% 0%, rgba(25,199,255,.16), transparent 55%),
        radial-gradient(1000px 600px at 50% 110%, rgba(55,255,139,.10), transparent 60%),
        linear-gradient(180deg, #05070c 0%, #070a0f 35%, #05070c 100%);
    }
    .grid-bg{position:fixed; inset:0; pointer-events:none; opacity:.12; z-index:-1;
      background-image:linear-gradient(to right, rgba(55,255,139,.14) 1px, transparent 1px),linear-gradient(to bottom, rgba(55,255,139,.10) 1px, transparent 1px); background-size:46px 46px; mask-image:radial-gradient(900px 600px at 40% 10%, #000 60%, transparent 100%);}
    .wrap{max-width:1280px; margin:0 auto; padding:24px 22px 80px}
    .topbar{display:flex; justify-content:space-between; gap:14px; border-bottom:1px solid var(--line2); padding-bottom:14px; margin-bottom:22px; font-family:var(--mono); font-size:11px; letter-spacing:.16em; color:var(--muted); text-transform:uppercase}
    .topbar .left{color:var(--green)} .topbar .right{text-align:right}
    .herorow{display:grid; grid-template-columns:1.5fr .9fr; gap:18px} @media (max-width:1000px){.herorow{grid-template-columns:1fr}}
    .hero,.card{background:linear-gradient(180deg, rgba(11,18,32,.95), rgba(8,14,26,.92)); border:1px solid var(--line); border-radius:22px; box-shadow:var(--shadow)}
    .hero{padding:28px 28px 24px; border-top:2px solid var(--cyan)}
    .hero h1{font-size:60px; line-height:.97; margin:0 0 18px; letter-spacing:-.5px; font-weight:800} @media (max-width:700px){.hero h1{font-size:40px}}
    .hero p{color:var(--muted); font-size:15px; line-height:1.55; max-width:700px; margin:0 0 18px}
    .chiprow{display:flex; flex-wrap:wrap; gap:8px}
    .meta-chip,.toolchip,.st{font-family:var(--mono); font-size:11px; border:1px solid var(--line); border-radius:999px; background:rgba(6,10,18,.4)}
    .meta-chip{color:var(--muted); padding:7px 12px}
    .toolchip{padding:6px 12px; color:var(--cyan); text-decoration:none}
    .toolchip.active{color:var(--green); border-color:currentColor}
    .side{display:flex; flex-direction:column; gap:14px}
    .bluf,.corr{border-radius:14px; padding:16px 18px}
    .bluf{border:1px solid var(--warn); border-left:4px solid var(--warn); background:linear-gradient(180deg, rgba(255,204,102,.06), rgba(11,18,32,.92))}
    .corr{border:1px solid var(--green); border-left:4px solid var(--green); background:linear-gradient(180deg, rgba(55,255,139,.06), rgba(11,18,32,.92))}
    .bluf .lbl,.corr .lbl{font-family:var(--mono); font-size:10px; letter-spacing:.18em; text-transform:uppercase}
    .bluf .lbl{color:var(--warn)} .corr .lbl{color:var(--green)} .bluf p,.corr p{color:var(--muted); font-size:13.5px; line-height:1.55; margin:6px 0 0}
    .section{margin-top:34px}
    .sh{display:flex; justify-content:space-between; align-items:baseline; gap:14px; padding-bottom:10px; border-bottom:1px solid var(--line2); margin-bottom:14px}
    .sh h2{margin:0; font-size:24px; font-weight:600; letter-spacing:-.2px} .sh .note{font-family:var(--mono); font-size:11px; color:var(--muted2); letter-spacing:.16em; text-transform:uppercase}
    .kpis{display:grid; grid-template-columns:repeat(6,1fr); gap:12px} @media (max-width:1100px){.kpis{grid-template-columns:repeat(3,1fr)}} @media (max-width:640px){.kpis{grid-template-columns:repeat(2,1fr)}}
    .kpi{border:1px solid var(--line); border-radius:14px; padding:14px 14px 12px; background:linear-gradient(180deg, rgba(11,18,32,.85), rgba(8,14,26,.65))}
    .kpi .v{font-family:var(--mono); font-size:26px; font-weight:600; letter-spacing:-.5px} .kpi .lbl{font-family:var(--mono); font-size:10px; letter-spacing:.18em; text-transform:uppercase; color:var(--muted); margin-top:6px} .kpi .h{font-size:12px; color:var(--muted); line-height:1.45; margin-top:8px}
    .green{color:var(--green)} .cyan{color:var(--cyan)} .amber{color:var(--warn)} .red{color:var(--bad)} .plum{color:var(--plum)} .white{color:var(--text)}
    .board{display:grid; grid-template-columns:repeat(2,1fr); gap:14px} @media (max-width:1000px){.board{grid-template-columns:1fr}}
    .pcard{border:1px solid var(--line); border-radius:16px; padding:18px 20px; background:linear-gradient(180deg, rgba(11,18,32,.85), rgba(8,14,26,.65))}
    .pcard h3{margin:6px 0 8px; font-size:19px; font-weight:600} .pcard .pdesc{font-size:13.5px; color:var(--muted); line-height:1.55; margin:0 0 12px}
    .pcard .pnum{font-family:var(--mono); font-size:22px; font-weight:600; color:var(--green)} .pcard .ptop{display:flex; justify-content:space-between; align-items:center; margin-bottom:8px}
    .pcard .ppri{font-family:var(--mono); font-size:10px; padding:5px 10px; border-radius:999px; border:1px solid var(--line); color:var(--green); letter-spacing:.14em; background:rgba(55,255,139,.06)}
    .ttbl{width:100%; border-collapse:separate; border-spacing:0; border:1px solid var(--line); border-radius:14px; overflow:hidden}
    .ttbl th,.ttbl td{padding:13px 14px; text-align:left; font-size:13.5px; vertical-align:top}
    .ttbl thead th{font-family:var(--mono); font-size:11px; letter-spacing:.16em; text-transform:uppercase; color:var(--muted2); border-bottom:1px solid var(--line); background:rgba(11,18,32,.5)}
    .ttbl tbody tr:hover{background:rgba(55,255,139,.03)} .ttbl td,.ttbl td *{color:var(--muted)} .ttbl b{color:var(--text)}
    .st{display:inline-block; padding:4px 9px; font-size:10px; letter-spacing:.1em; text-transform:uppercase; border:1px solid currentColor}
    .st.good{color:var(--green)} .st.watch{color:var(--warn)} .st.critical{color:var(--bad)}
    footer{margin-top:30px; padding-top:14px; border-top:1px dashed var(--line2); display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; font-family:var(--mono); font-size:11px; color:var(--muted2); letter-spacing:.08em}
    a{color:var(--cyan); text-decoration:none} a:hover{text-decoration:underline}
  </style>
</head>
<body>
<div class="grid-bg"></div>
<div class="wrap">
  <div class="topbar">
    <div class="left">KINETIC GAIN · KYC refresh lane</div>
    <div class="right">
      <div>FinTech KYC refresh + review routing</div>
      <div>Beneficial ownership · sanctions recency · account-safe release posture</div>
    </div>
  </div>
  <div class="herorow">
    <section class="hero">
      <div class="chiprow">
        <span class="meta-chip">{$safeEyebrow}</span>
        <span class="meta-chip">CNAME · kyc.kineticgain.com</span>
        <span class="meta-chip">PHP + static Pages bundle</span>
      </div>
      <h1>{$safeHero}</h1>
      <p>{$safeIntro}</p>
      <div class="bluf" style="margin:18px 0 18px;">
        <div class="lbl">Lead recommendation</div>
        <p><strong>Refresh-safe account posture</strong><br>{$leadRecommendation}</p>
      </div>
      <div class="chiprow">{$nav}</div>
    </section>
    <aside class="side">{$rightCardsHtml}</aside>
  </div>
  <section class="section">
    <div class="sh"><h2>Operator summary</h2><div class="note">beneficial-owner evidence + review discipline</div></div>
    <div class="kpis">
      <div class="kpi"><div class="v green">{$summary['caseCount']}</div><div class="lbl">Tracked cases</div><div class="h">Merchant, payout, treasury, and cross-border refresh lanes in one surface.</div></div>
      <div class="kpi"><div class="v cyan">{$summary['healthyCount']}</div><div class="lbl">Healthy lanes</div><div class="h">Aligned to current ownership evidence, sanctions recency, and review posture.</div></div>
      <div class="kpi"><div class="v amber">{$summary['watchCount']}</div><div class="lbl">Watch lanes</div><div class="h">Need document or screening repair before the next unblock decision.</div></div>
      <div class="kpi"><div class="v red">{$summary['criticalCount']}</div><div class="lbl">Critical lanes</div><div class="h">Unsafe to clear until one canonical refresh packet is restored.</div></div>
      <div class="kpi"><div class="v plum">{$summary['queueCount']}</div><div class="lbl">Queue artifacts</div><div class="h">Canonical bundles tying evidence, screening state, and final decision memos together.</div></div>
      <div class="kpi"><div class="v white">{$operatorPosture}</div><div class="lbl">Operator posture</div><div class="h">KYC refresh treated like an operating system, not an inbox of one-off cases.</div></div>
    </div>
  </section>
  {$body}
  <footer>
    <div>kyc-refresh-case-router · AGPL-3.0-or-later · synthetic demonstration data only</div>
    <div><a href="https://github.com/mizcausevic-dev/">GitHub</a> · <a href="https://www.linkedin.com/in/mirzacausevic/">LinkedIn</a> · <a href="https://kineticgain.com/">Kinetic Gain</a></div>
    <div>Routes: / · /kyc-lane · /refresh-queue · /review-posture · /verification · /docs</div>
  </footer>
</div>
</body>
</html>
HTML;
}

function render_nav(string $active): string
{
    $items = [
        '/' => 'Overview',
        '/kyc-lane' => 'KYC Lane',
        '/refresh-queue' => 'Refresh Queue',
        '/review-posture' => 'Review Posture',
        '/verification' => 'Verification',
        '/docs' => 'Docs',
    ];

    $html = '';
    foreach ($items as $href => $label) {
        $class = $active === $href ? 'toolchip active' : 'toolchip';
        $html .= '<a class="' . $class . '" href="' . htmlspecialchars($href, ENT_QUOTES) . '">' . htmlspecialchars($label, ENT_QUOTES) . '</a>';
    }

    return $html;
}

function render_side_cards(array $cards): string
{
    $html = '';
    foreach ($cards as $index => $card) {
        $class = $index === 0 ? 'bluf' : 'corr';
        $html .= '<article class="' . $class . '"><div class="lbl">' . htmlspecialchars($card['label'], ENT_QUOTES) . '</div><p><strong>' . htmlspecialchars($card['title'], ENT_QUOTES) . '</strong><br>' . htmlspecialchars($card['body'], ENT_QUOTES) . '</p></article>';
    }
    return $html;
}

function render_overview_cards(): string
{
    $service = new KycRefreshCaseRouterService();
    $lanes = array_slice($service->kycLanes(), 0, 4);
    $html = '<div class="board">';
    foreach ($lanes as $index => $lane) {
        $html .= '<article class="pcard"><div class="ptop"><div class="pnum">0' . ($index + 1) . '</div><div class="ppri">' . htmlspecialchars((string) $lane['status'], ENT_QUOTES) . '</div></div><h3>' . htmlspecialchars((string) $lane['case'], ENT_QUOTES) . '</h3><p class="pdesc">' . htmlspecialchars((string) $lane['proof'], ENT_QUOTES) . '</p><p class="pdesc"><strong>Risk:</strong> ' . htmlspecialchars((string) $lane['risk'], ENT_QUOTES) . '</p><p class="pdesc"><strong>Next:</strong> ' . htmlspecialchars((string) $lane['nextAction'], ENT_QUOTES) . '</p></article>';
    }
    return $html . '</div>';
}

function render_overview(): string
{
    $body = '<section class="section"><div class="sh"><h2>Overview</h2><div class="note">where KYC refresh pressure surfaces first</div></div>' . render_overview_cards() . '</section>'
        . '<section class="section"><div class="sh"><h2>Board questions this answers</h2><div class="note">identity exposure · unblock cost · control investment</div></div><div class="board">'
        . '<article class="pcard"><div class="ptop"><div class="pnum">01</div><div class="ppri">EXPOSURE</div></div><h3>Which accounts are unsafe to advance?</h3><p class="pdesc">Beneficial-owner packet gaps, stale sanctions screening, expiry drift, and missing final review memos stay visible before onboarding, payout, or treasury teams move the account forward.</p></article>'
        . '<article class="pcard"><div class="ptop"><div class="pnum">02</div><div class="ppri">SAVINGS</div></div><h3>Where is manual review creating avoidable drag?</h3><p class="pdesc">The router ties documents, screening state, account lane, owner, and next action together so FinCrime, onboarding, and treasury teams stop rebuilding the same KYC case packet.</p></article>'
        . '<article class="pcard"><div class="ptop"><div class="pnum">03</div><div class="ppri">INVEST</div></div><h3>Which refresh control deserves automation first?</h3><p class="pdesc">Critical and watch lanes show whether ownership evidence, sanctions recency, expiry alerts, or final decision memo alignment should receive the next platform investment.</p></article>'
        . '</div></section>'
        . '<section class="section"><div class="sh"><h2>Evidence model</h2><div class="note">signal · proof · decision</div></div><table class="ttbl"><thead><tr><th>Signal</th><th>Owner</th><th>Required proof</th><th>Decision supported</th></tr></thead><tbody>'
        . '<tr><td><b>Beneficial-owner evidence</b></td><td>Onboarding Operations</td><td>Ownership packet, entity record, document recency, reviewer memo</td><td>Approve refresh, request repair, or block release</td></tr>'
        . '<tr><td><b>Sanctions screening recency</b></td><td>FinCrime Operations</td><td>Screening timestamp, list source, exception note, escalation owner</td><td>Clear account, keep on watch, or escalate risk</td></tr>'
        . '<tr><td><b>Final unblock posture</b></td><td>Platform Risk</td><td>Release memo, payout status, treasury check, account-safe decision</td><td>Advance, hold, or reroute the account lane</td></tr>'
        . '</tbody></table></section>';

    return shell(
        '/',
        'KYC Refresh Case Router',
        'kyc refresh case router',
        'Keep beneficial-owner evidence, screening recency, and unblock posture in the same operator lane.',
        'This operator surface makes KYC refresh explicit: which lanes are safe, which review packets are stale, and where payout, onboarding, treasury, or platform-risk teams still need to repair evidence before an account-safe decision is made.',
        $body,
        [
            ['label' => 'Core offer', 'title' => 'KYC refresh control plane', 'body' => 'Ownership evidence, review packets, and unblock posture tied together in one inspectable surface.'],
            ['label' => 'Buyer fit', 'title' => 'FinTech risk and operations', 'body' => 'For FinCrime, onboarding, treasury, payout, and identity-risk teams handling refresh at scale.'],
            ['label' => 'Execution style', 'title' => 'Account-safe routing', 'body' => 'Treat refresh evidence, screening recency, and decision memos as release artifacts.'],
        ]
    );
}

function render_kyc_lane(): string
{
    $service = new KycRefreshCaseRouterService();
    $rows = '';
    foreach ($service->kycLanes() as $lane) {
        $rows .= '<tr><td><b>' . htmlspecialchars((string) $lane['case'], ENT_QUOTES) . '</b><br>' . htmlspecialchars((string) $lane['cohort'], ENT_QUOTES) . '</td><td>' . htmlspecialchars((string) $lane['owner'], ENT_QUOTES) . '</td><td>' . htmlspecialchars((string) $lane['packet'], ENT_QUOTES) . '</td><td>' . htmlspecialchars((string) $lane['risk'], ENT_QUOTES) . '</td><td><span class="st ' . status_class((string) $lane['status']) . '">' . htmlspecialchars((string) $lane['status'], ENT_QUOTES) . '</span></td></tr>';
    }
    $body = '<section class="section"><div class="sh"><h2>KYC lane</h2><div class="note">case-by-case review posture</div></div><table class="ttbl"><thead><tr><th>Case</th><th>Owner</th><th>Packet</th><th>Risk</th><th>Status</th></tr></thead><tbody>' . $rows . '</tbody></table></section>';

    return shell(
        '/kyc-lane',
        'KYC Lane · KYC Refresh Case Router',
        'kyc lane',
        'Review every refresh lane before ownership or screening drift leaks into the next account decision.',
        'The KYC lane shows which refresh cases are safe, which are drifting, and which should block payout, onboarding, or treasury actions until the review packet is whole again.',
        $body,
        [
            ['label' => 'Signal', 'title' => 'Case-level clarity', 'body' => 'See merchant, payout, treasury, and cross-border refresh posture in one table.'],
            ['label' => 'Pressure', 'title' => 'Release-window timing', 'body' => 'Pair each lane with the actual regulatory and account risk if it advances stale.'],
            ['label' => 'Control', 'title' => 'Named owner + next fix', 'body' => 'Every drift item has one owner and one next action before release.'],
        ]
    );
}

function render_refresh_queue(): string
{
    $service = new KycRefreshCaseRouterService();
    $cards = '<div class="board">';
    foreach ($service->refreshQueue() as $index => $artifact) {
        $cards .= '<article class="pcard"><div class="ptop"><div class="pnum">Q' . ($index + 1) . '</div><div class="ppri">' . htmlspecialchars((string) $artifact['status'], ENT_QUOTES) . '</div></div><h3>' . htmlspecialchars((string) $artifact['artifact'], ENT_QUOTES) . '</h3><p class="pdesc">' . htmlspecialchars((string) $artifact['purpose'], ENT_QUOTES) . '</p><p class="pdesc"><strong>Owner:</strong> ' . htmlspecialchars((string) $artifact['owner'], ENT_QUOTES) . '</p><p class="pdesc"><strong>Anchor:</strong> ' . htmlspecialchars((string) $artifact['anchor'], ENT_QUOTES) . '</p></article>';
    }
    $body = '<section class="section"><div class="sh"><h2>Refresh queue</h2><div class="note">canonical evidence + unblock packets</div></div>' . $cards . '</div></section>';

    return shell(
        '/refresh-queue',
        'Refresh Queue · KYC Refresh Case Router',
        'refresh queue',
        'Keep ownership packets, screening exports, and release memos tied to one governed case path.',
        'This route turns KYC governance into an evidence map: which artifacts are canonical, who owns them, and where the live queue still points at stale review state.',
        $body,
        [
            ['label' => 'Evidence', 'title' => 'Canonical ownership packets', 'body' => 'Make beneficial-owner and document evidence discoverable to risk and treasury systems.'],
            ['label' => 'Screening', 'title' => 'Fresh sanctions anchors', 'body' => 'Queue outputs should reflect the latest screening posture before the next unblock.'],
            ['label' => 'Decisioning', 'title' => 'One final memo', 'body' => 'The visible account posture and the actual review packet need to say the same thing.'],
        ]
    );
}

function render_review_posture(): string
{
    $service = new KycRefreshCaseRouterService();
    $rows = '';
    foreach ($service->verificationGates() as $gate) {
        $rows .= '<tr><td><b>' . htmlspecialchars((string) $gate['gate'], ENT_QUOTES) . '</b></td><td>' . htmlspecialchars((string) $gate['detail'], ENT_QUOTES) . '</td><td><span class="st ' . status_class((string) $gate['status']) . '">' . htmlspecialchars((string) $gate['status'], ENT_QUOTES) . '</span></td></tr>';
    }
    $body = '<section class="section"><div class="sh"><h2>Review posture</h2><div class="note">account-safe release gate</div></div><table class="ttbl"><thead><tr><th>Gate</th><th>Detail</th><th>Status</th></tr></thead><tbody>' . $rows . '</tbody></table></section>';

    return shell(
        '/review-posture',
        'Review Posture · KYC Refresh Case Router',
        'review posture',
        'Block account release when ownership evidence, screening state, and final decision posture disagree.',
        'The review posture route keeps onboarding, treasury, payout, and platform-risk teams aligned around one decision: is the refresh case safe to advance with the current evidence packet?',
        $body,
        [
            ['label' => 'Signal', 'title' => 'No silent refresh drift', 'body' => 'Review should stop before an account moves on stale ownership or screening state.'],
            ['label' => 'Proof', 'title' => 'Evidence + decision alignment', 'body' => 'Visible account posture, document state, and final review memo should converge on one truth.'],
            ['label' => 'Buyer value', 'title' => 'Safer FinTech operations', 'body' => 'Keep merchant, treasury, and payout flows defensible across every surface.'],
        ]
    );
}

function render_verification(): string
{
    $service = new KycRefreshCaseRouterService();
    $rows = '';
    foreach ($service->verificationGates() as $gate) {
        $rows .= '<tr><td><b>' . htmlspecialchars((string) $gate['gate'], ENT_QUOTES) . '</b></td><td>' . htmlspecialchars((string) $gate['detail'], ENT_QUOTES) . '</td><td><span class="st ' . status_class((string) $gate['status']) . '">' . htmlspecialchars((string) $gate['status'], ENT_QUOTES) . '</span></td></tr>';
    }
    $body = '<section class="section"><div class="sh"><h2>Verification</h2><div class="note">proof gates before account actions move</div></div><table class="ttbl"><thead><tr><th>Gate</th><th>Detail</th><th>Status</th></tr></thead><tbody>' . $rows . '</tbody></table></section>';

    return shell(
        '/verification',
        'Verification · KYC Refresh Case Router',
        'verification',
        'Show the proof gates that must clear before the refresh case becomes release-safe.',
        'The verification route makes the release criteria explicit: which evidence anchors are required, which recency checks are mandatory, and where the final unblock memo still needs repair.',
        $body,
        [
            ['label' => 'Gatekeeping', 'title' => 'No blind account unlocks', 'body' => 'Every unblock depends on one visible set of review gates.'],
            ['label' => 'Evidence', 'title' => 'Proof before release', 'body' => 'Ownership, screening, and expiry posture must converge before the next state change.'],
            ['label' => 'Auditability', 'title' => 'Inspectable decision path', 'body' => 'One route shows exactly why a case is safe, blocked, or still on watch.'],
        ]
    );
}

function render_docs(): string
{
    $body = <<<HTML
<section class="section">
  <div class="sh"><h2>Docs</h2><div class="note">how the WordPress and static layer fit together</div></div>
  <div class="board">
    <article class="pcard">
      <h3>WordPress refresh hooks</h3>
      <p class="pdesc">The plugin demonstrates how reviewed KYC refresh fragments can be exposed through a shortcode and REST endpoint without burying them inside theme-only settings.</p>
      <ul>
        <li>Shortcode output keeps refresh payloads readable to admins and reviewers.</li>
        <li>REST route gives external systems one canonical KYC refresh anchor.</li>
      </ul>
    </article>
    <article class="pcard">
      <h3>Kinetic Gain fit</h3>
      <p class="pdesc">This repo treats KYC refresh governance as a release system, not a hidden case queue.</p>
      <ul>
        <li>PHP / WordPress lane for the language atlas.</li>
        <li>FinTech operator surface for the industry atlas.</li>
        <li>Embedded tie-back for regulated identity and payout workflows.</li>
      </ul>
    </article>
  </div>
</section>
HTML;

    return shell(
        '/docs',
        'Docs · KYC Refresh Case Router',
        'docs',
        'Document the KYC refresh path so the release system can enforce it.',
        'The docs route explains why the plugin exists, how the public control plane is shaped, and where the machine-readable refresh snapshot fits into the broader Kinetic Gain stack.',
        $body,
        [
            ['label' => 'Language atlas', 'title' => 'PHP / WordPress surface', 'body' => 'This expands the language atlas with a WordPress-native FinTech review control plane.'],
            ['label' => 'Industry atlas', 'title' => 'FinTech anchor', 'body' => 'The same primitive can power KYC, treasury, payout, and identity review workflows.'],
            ['label' => 'Embedded tie-back', 'title' => 'Governed release posture', 'body' => 'Use the same surface shape when review packets and release decisions need to stay in lockstep.'],
        ]
    );
}
