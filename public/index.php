<?php

declare(strict_types=1);

use KycRefreshCaseRouter\Services\KycRefreshCaseRouterService;

require __DIR__ . '/../src/Services/KycRefreshCaseRouterService.php';
require __DIR__ . '/../src/Views/render.php';

$service = new KycRefreshCaseRouterService();
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

if (str_starts_with($path, '/api/')) {
    header('Content-Type: application/json; charset=utf-8');

    $payload = match ($path) {
        '/api/dashboard/summary' => $service->summary(),
        '/api/kyc-lane' => $service->kycLanes(),
        '/api/refresh-queue' => $service->refreshQueue(),
        '/api/verification' => $service->verificationGates(),
        '/api/sample' => $service->payload(),
        default => ['error' => 'Not found'],
    };

    if ($payload === ['error' => 'Not found']) {
        http_response_code(404);
    }

    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    return;
}

$html = match ($path) {
    '/' => KycRefreshCaseRouter\Views\render_overview(),
    '/kyc-lane' => KycRefreshCaseRouter\Views\render_kyc_lane(),
    '/refresh-queue' => KycRefreshCaseRouter\Views\render_refresh_queue(),
    '/review-posture' => KycRefreshCaseRouter\Views\render_review_posture(),
    '/verification' => KycRefreshCaseRouter\Views\render_verification(),
    '/docs' => KycRefreshCaseRouter\Views\render_docs(),
    default => null,
};

if ($html === null) {
    http_response_code(404);
    echo 'Not found';
    return;
}

header('Content-Type: text/html; charset=utf-8');
echo $html;
