<?php

declare(strict_types=1);

namespace KycRefreshCaseRouter\Services;

final class KycRefreshCaseRouterService
{
    /** @var array<string, mixed> */
    private array $payload;

    public function __construct()
    {
        /** @var array<string, mixed> $payload */
        $payload = require __DIR__ . '/../Data/sample_kyc_refresh_case_router.php';
        $this->payload = $payload;
    }

    /**
     * @return array<string, mixed>
     */
    public function summary(): array
    {
        $lanes = $this->kycLanes();
        $critical = array_values(array_filter($lanes, static fn(array $lane): bool => $lane['status'] === 'critical'));
        $watch = array_values(array_filter($lanes, static fn(array $lane): bool => $lane['status'] === 'watch'));
        $healthy = array_values(array_filter($lanes, static fn(array $lane): bool => $lane['status'] === 'healthy'));

        return [
            'caseCount' => count($lanes),
            'healthyCount' => count($healthy),
            'watchCount' => count($watch),
            'criticalCount' => count($critical),
            'queueCount' => count($this->refreshQueue()),
            'operatorPosture' => (string) $this->payload['summary']['operatorPosture'],
            'leadRecommendation' => (string) $this->payload['summary']['leadRecommendation'],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function kycLanes(): array
    {
        /** @var array<int, array<string, mixed>> $lanes */
        $lanes = $this->payload['kycLanes'];

        return $lanes;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function refreshQueue(): array
    {
        /** @var array<int, array<string, mixed>> $queue */
        $queue = $this->payload['refreshQueue'];

        return $queue;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function verificationGates(): array
    {
        /** @var array<int, array<string, mixed>> $gates */
        $gates = $this->payload['verificationGates'];

        return $gates;
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return [
            'product' => 'KYC Refresh Case Router',
            'purpose' => 'WordPress and FinTech operator surface for KYC refresh routing, beneficial-owner drift, review evidence, and account-safe remediation posture.',
            'routes' => [
                '/',
                '/kyc-lane',
                '/refresh-queue',
                '/review-posture',
                '/verification',
                '/docs',
            ],
            'priorities' => [
                'Keep beneficial-owner evidence, screening recency, and unblock posture in the same operator lane.',
                'Expose stale refresh packets before payout, onboarding, or treasury systems act on them.',
                'Make document expiry, sanction checks, and final review memos point at the same canonical case record.',
                'Turn KYC refresh governance into a visible operating system instead of scattered review queues and ticket residue.',
            ],
            'entity' => (string) $this->payload['summary']['entity'],
        ];
    }
}
