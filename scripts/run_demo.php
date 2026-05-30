<?php

declare(strict_types=1);

require __DIR__ . '/../src/Services/KycRefreshCaseRouterService.php';

$service = new KycRefreshCaseRouter\Services\KycRefreshCaseRouterService();
$summary = $service->summary();

echo "Product: KYC Refresh Case Router\n";
echo "Tracked cases: {$summary['caseCount']}\n";
echo "Healthy lanes: {$summary['healthyCount']}\n";
echo "Watch lanes: {$summary['watchCount']}\n";
echo "Critical lanes: {$summary['criticalCount']}\n";
echo "Queue items: {$summary['queueCount']}\n";
echo "Lead recommendation: {$summary['leadRecommendation']}\n";
