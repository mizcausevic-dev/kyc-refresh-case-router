<?php

declare(strict_types=1);

return [
    'summary' => [
        'entity' => 'Kinetic Gain FinTech Risk Ops',
        'operatorPosture' => 'Refresh-safe KYC review operation',
        'leadRecommendation' => 'Escalate the marketplace payouts lane now: beneficial-owner refresh evidence, sanction-screen recency, and document expiry posture are still split across three systems and the next payout unlock should stay blocked until one canonical packet is rebuilt.',
    ],
    'kycLanes' => [
        [
            'case' => 'Marketplace payouts - beneficial owner refresh',
            'cohort' => 'High-throughput merchant payouts',
            'owner' => 'FinCrime operations lead',
            'packet' => 'Q2 beneficial-owner refresh bundle',
            'proof' => 'Ownership records were partially updated, but the controlling-person document trail is still stale in the canonical review packet.',
            'risk' => 'Account-safe payout posture can break if stale ownership state clears ahead of the real review outcome.',
            'nextAction' => 'Merge refreshed beneficial-owner evidence, rerun sanctions screening, and regenerate the approval packet.',
            'status' => 'critical',
        ],
        [
            'case' => 'SMB onboarding - address refresh queue',
            'cohort' => 'Domestic SMB accounts',
            'owner' => 'Lifecycle risk operations',
            'packet' => 'Address-proof exception queue',
            'proof' => 'Document uploads are present, but two cases still lack adjudicated proof-of-address outcomes.',
            'risk' => 'Stale refresh queues can leave customer state ambiguous across onboarding and fraud systems.',
            'nextAction' => 'Close the two unresolved address proofs and republish the queue snapshot.',
            'status' => 'watch',
        ],
        [
            'case' => 'Treasury admin renewal lane',
            'cohort' => 'Privileged finance admins',
            'owner' => 'Platform identity risk',
            'packet' => 'Privileged access recertification bundle',
            'proof' => 'Identity proofing, role review, and sanctions recency all converge on one reviewed packet.',
            'risk' => 'Low. Current review lane is refresh-safe.',
            'nextAction' => 'Monitor only.',
            'status' => 'healthy',
        ],
        [
            'case' => 'Cross-border contractor wallet refresh',
            'cohort' => 'International payout recipients',
            'owner' => 'Cross-border compliance ops',
            'packet' => 'Wallet recipient refresh bundle',
            'proof' => 'Sanctions recency is current, but passport expiration evidence drifted away from the main review packet.',
            'risk' => 'Cross-border payout approvals can move on stale identity proof if the expiry anchor is missed.',
            'nextAction' => 'Restore document-expiry evidence to the canonical packet before the next release window.',
            'status' => 'watch',
        ],
        [
            'case' => 'Enterprise treasury signer review',
            'cohort' => 'Enterprise counterparty signers',
            'owner' => 'Treasury governance',
            'packet' => 'Signer attestation and refresh packet',
            'proof' => 'Signer roster, recertification notes, and review evidence are aligned.',
            'risk' => 'Low. No visible refresh drift.',
            'nextAction' => 'Monitor only.',
            'status' => 'healthy',
        ],
    ],
    'refreshQueue' => [
        [
            'artifact' => 'Beneficial-owner review packet',
            'purpose' => 'Canonical bundle for ownership evidence, screening recency, and controlling-person approval notes.',
            'owner' => 'FinCrime operations',
            'anchor' => '/evidence/beneficial-owner-review-packet',
            'status' => 'critical',
        ],
        [
            'artifact' => 'Document expiry exception log',
            'purpose' => 'Proof that expiring identity documents are reconciled before release-safe account actions continue.',
            'owner' => 'Identity review operations',
            'anchor' => '/evidence/document-expiry-log',
            'status' => 'watch',
        ],
        [
            'artifact' => 'Sanctions recency export',
            'purpose' => 'Shows when watchlist checks last ran and whether the live queue still maps to current screening state.',
            'owner' => 'Screening platform team',
            'anchor' => '/evidence/sanctions-recency-export',
            'status' => 'approved',
        ],
        [
            'artifact' => 'Payout hold release memo',
            'purpose' => 'Connects KYC refresh outcomes to the final unblock or continued hold decision.',
            'owner' => 'Risk + treasury',
            'anchor' => '/evidence/payout-hold-release-memo',
            'status' => 'watch',
        ],
    ],
    'verificationGates' => [
        [
            'gate' => 'Beneficial-owner packet matches the reviewed source of truth',
            'detail' => 'Block release if the live ownership packet drifts from the approved review state.',
            'status' => 'critical',
        ],
        [
            'gate' => 'Document expiry evidence resolves to one canonical case posture',
            'detail' => 'Block downstream account actions when the queue and document store disagree on expiry outcome.',
            'status' => 'watch',
        ],
        [
            'gate' => 'Sanctions screening recency is fresh for every routed case',
            'detail' => 'Keep payout and onboarding systems from advancing on stale screening state.',
            'status' => 'approved',
        ],
        [
            'gate' => 'Release decision memo exists before account unblock',
            'detail' => 'Ensure every refresh case ends with one accountable unblock or hold memo.',
            'status' => 'approved',
        ],
    ],
];
