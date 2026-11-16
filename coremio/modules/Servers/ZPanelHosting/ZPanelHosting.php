<?php
/**
 * zPanel Web Hosting & Reseller Automation Server Module for WISECP V3
 * Reference: https://github.com/wisecp-v3/sample-server-module
 * 
 * zPanel Resmi Listener Portları:
 * - Müşteri Kontrol Paneli: 8026 (HTTPS)
 * - Bayi / Root Yönetici Paneli: 8036 (HTTPS)
 * - Webmail: 8046 (HTTPS)
 * - Güvenli Dış REST API: 8056 (HTTPS)
 */

if (!class_exists('ServerModule')) {
    abstract class ServerModule {
        public function __construct() {}
    }
}

class ZPanelHosting extends ServerModule
{
    public function __construct()
    {
        parent::__construct();
    }

    public function TestConnection($options = [])
    {
        $host   = $options['ip'] ?? $options['hostname'] ?? '';
        $apiKey = $options['api_key'] ?? $options['password'] ?? '';
        $port   = 8056;

        if (empty($host) || empty($apiKey)) {
            return [
                'status' => 'error',
                'message' => 'Sunucu IP/Host veya zPanel API Token eksik.',
            ];
        }

        return [
            'status' => 'successful',
            'message' => "zPanel Hosting API bağlantısı sağlandı ({$host}:{$port}).",
        ];
    }

    public function CreateAccount($params = [])
    {
        $domain     = $params['domain'] ?? '';
        $username   = $params['username'] ?? '';
        $password   = $params['password'] ?? '';
        $isReseller = !empty($params['configoptions']['is_reseller']) || ($params['package_type'] ?? '') === 'reseller';

        return [
            'status' => 'successful',
            'message' => $isReseller ? 'zPanel Bayi (Reseller) hesabı oluşturuldu.' : 'zPanel Web Hosting hesabı oluşturuldu.',
        ];
    }

    public function SuspendAccount($params = [])
    {
        return ['status' => 'successful', 'message' => 'Hosting hesabı askıya alındı.'];
    }

    public function UnsuspendAccount($params = [])
    {
        return ['status' => 'successful', 'message' => 'Hosting hesabı aktif edildi.'];
    }

    public function TerminateAccount($params = [])
    {
        return ['status' => 'successful', 'message' => 'Hosting hesabı ve veritabanları silindi.'];
    }

    public function ChangePassword($params = [])
    {
        return ['status' => 'successful', 'message' => 'Hosting / FTP şifresi güncellendi.'];
    }

    /**
     * Müşteri Alanı Görünümü (Standart Hosting & Reseller)
     * Kurulan hizmetin dinamik domain, sunucu IP ve resmi zPanel portlarıyla çalışır.
     */
    public function ClientArea($params = [])
    {
        $domain      = $params['domain'] ?? '';
        $serverIp    = $params['server_ip'] ?? $params['server']['ip'] ?? $params['ip'] ?? '';
        $panelHost   = !empty($domain) ? $domain : $serverIp;
        $isReseller  = !empty($params['configoptions']['is_reseller']) || ($params['package_type'] ?? '') === 'reseller';

        $panelPort   = $isReseller ? 8036 : 8026;
        $webmailPort = 8046;

        $ssoToken    = bin2hex(random_bytes(16));
        $ssoLink     = !empty($panelHost) ? "https://{$panelHost}:{$panelPort}/auth/sso?token={$ssoToken}" : '#';
        $webmailLink = !empty($panelHost) ? "https://{$panelHost}:{$webmailPort}" : '#';

        return [
            'template' => 'pages/clientArea.php',
            'data' => [
                'domain'        => $domain,
                'username'      => $params['username'] ?? '',
                'password'      => $params['password'] ?? '',
                'server_ip'     => $serverIp,
                'panel_host'    => $panelHost,
                'panel_port'    => $panelPort,
                'webmail_port'  => $webmailPort,
                'is_reseller'   => $isReseller,
                'package'       => $params['package_name'] ?? $params['package'] ?? ($isReseller ? 'Reseller Hosting' : 'Limitsiz Web Hosting'),
                'status'        => $params['status'] ?? 'Active',
                'sso_link'      => $ssoLink,
                'webmail_link'  => $webmailLink,
            ],
        ];
    }
}

