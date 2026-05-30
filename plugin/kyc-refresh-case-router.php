<?php
/**
 * Plugin Name: KYC Refresh Case Router
 * Plugin URI: https://kyc.kineticgain.com/
 * Description: Publishes KYC refresh snapshots, beneficial-owner review packets, and machine-readable FinTech workflow payloads for WordPress sites.
 * Version: 0.1.0
 * Author: Kinetic Gain
 * License: AGPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/agpl-3.0.html
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

if (! function_exists('kg_kyc_refresh_snapshot_payload')) {
    /**
     * @return array<string, mixed>
     */
    function kg_kyc_refresh_snapshot_payload(): array
    {
        return [
            'entity' => 'Kinetic Gain FinTech Risk Ops',
            'kit' => 'KYC Refresh Case Router',
            'version' => '0.1.0',
            'updatedAt' => gmdate('c'),
            'kycLanes' => [
                'marketplace-payouts-bo-refresh',
                'smb-onboarding-address-refresh',
                'treasury-admin-renewal',
                'cross-border-wallet-refresh',
                'enterprise-signer-review',
            ],
            'operatorNote' => 'Synthetic demonstration payload only. Review KYC policy, screening posture, and account release workflow before production use.',
        ];
    }
}

if (! function_exists('kg_render_kyc_refresh_snapshot')) {
    function kg_render_kyc_refresh_snapshot(): string
    {
        $payload = kg_kyc_refresh_snapshot_payload();

        return '<pre class="kg-kyc-refresh-snapshot">' . esc_html(wp_json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) . '</pre>';
    }
}

add_shortcode('kg_kyc_refresh_snapshot', 'kg_render_kyc_refresh_snapshot');

add_action('rest_api_init', static function (): void {
    register_rest_route(
        'kg-fintech-ops/v1',
        '/kyc-refresh-snapshot',
        [
            'methods' => 'GET',
            'permission_callback' => '__return_true',
            'callback' => static function () {
                return rest_ensure_response(kg_kyc_refresh_snapshot_payload());
            },
        ]
    );
});
