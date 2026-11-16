<?php
/**
 * zPanel Web Hosting Server Module for WISECP V3
 * Reference: https://github.com/wisecp-v3/sample-server-module
 * Port Mimarisi: 8026 (Client), 8036 (Root), 8046 (Webmail), 8056 (API)
 */

if (!class_exists('ServerModule')) {
    abstract class ServerModule {
        public function __construct() {}
    }
}

require_once __DIR__ . '/lib/ApiClient.php';
use Coremio\Modules\Servers\ZPanelHosting\Lib\ApiClient;

class ZPanelHosting extends ServerModule
{
    private function getClient(array $params): ApiClient
    {
        $server = $params['server'] ?? [];
        $host   = $server['ip'] ?? $server['hostname'] ?? $params['domain'] ?? '';
        $apiKey = $server['password'] ?? $server['access_hash'] ?? '';
        return new ApiClient($host, $apiKey, 8056);
    }

    public function TestConnection($options = [])
    {
        $host   = $options['ip'] ?? $options['hostname'] ?? '';
        $apiKey = $options['api_key'] ?? $options['password'] ?? '';
        $client = new ApiClient($host, $apiKey, 8056);

        $res = $client->testConnection();
        if ($res['status']) {
            return ['status' => 'successful', 'message' => "zPanel Hosting API bağlantısı başarılı ({$host}:8056)."];
        }
        return ['status' => 'error', 'message' => $res['message'] ?: 'Bağlantı kurulamadı.'];
    }

    public function CreateAccount($params = [])
    {
        $client = $this->getClient($params);
        $username = $params['user']['username'] ?? $params['username'] ?? '';
        $password = $params['user']['password'] ?? $params['password'] ?? '';
        $domain   = $params['domain'] ?? '';
        $email    = $params['user']['email'] ?? "{$username}@{$domain}";
        $fullName = trim(($params['user']['name'] ?? '') . ' ' . ($params['user']['surname'] ?? ''));

        // 1. Müşteri Hesabı Oluştur
        $custRes = $client->request('POST', 'customers', [
            'username'    => $username,
            'password'    => $password,
            'displayName' => $fullName ?: $username,
            'email'       => $email,
        ]);

        if (!$custRes['status']) {
            return ['status' => 'error', 'message' => $custRes['message'] ?: 'Kullanıcı hesabı açılamadı.'];
        }

        $customerId = $custRes['data']['customer']['id'] ?? $client->findCustomerId($username);

        // 2. Alan Adı Ekle
        if (!empty($domain) && !empty($customerId)) {
            $client->request('POST', 'domains', [
                'name'    => $domain,
                'ownerId' => $customerId,
                'kind'    => 'domain',
            ]);
        }

        return [
            'status' => 'successful',
            'message' => 'Web hosting hesabı ve alan adı başarıyla oluşturuldu.',
        ];
    }

    public function SuspendAccount($params = [])
    {
        $client = $this->getClient($params);
        $username = $params['user']['username'] ?? $params['username'] ?? '';
        $customerId = $client->findCustomerId($username);

        if (!$customerId) {
            return ['status' => 'successful', 'message' => 'Hesap zaten mevcut değil.'];
        }

        $res = $client->request('PATCH', "customers/{$customerId}", ['status' => 'suspended']);
        return [
            'status' => $res['status'] ? 'successful' : 'error',
            'message' => $res['status'] ? 'Hesap askıya alındı.' : $res['message'],
        ];
    }

    public function UnsuspendAccount($params = [])
    {
        $client = $this->getClient($params);
        $username = $params['user']['username'] ?? $params['username'] ?? '';
        $customerId = $client->findCustomerId($username);

        if (!$customerId) {
            return ['status' => 'error', 'message' => 'Hesap bulunamadı.'];
        }

        $res = $client->request('PATCH', "customers/{$customerId}", ['status' => 'active']);
        return [
            'status' => $res['status'] ? 'successful' : 'error',
            'message' => $res['status'] ? 'Hesap aktif edildi.' : $res['message'],
        ];
    }

    public function TerminateAccount($params = [])
    {
        $client = $this->getClient($params);
        $username = $params['user']['username'] ?? $params['username'] ?? '';
        $customerId = $client->findCustomerId($username);

        if (!$customerId) {
            return ['status' => 'successful', 'message' => 'Hesap zaten silinmiş.'];
        }

        $res = $client->request('DELETE', "customers/{$customerId}");
        return [
            'status' => $res['status'] ? 'successful' : 'error',
            'message' => $res['status'] ? 'Hesap silindi.' : $res['message'],
        ];
    }

    public function ChangePassword($params = [])
    {
        $client = $this->getClient($params);
        $username = $params['user']['username'] ?? $params['username'] ?? '';
        $newPass  = $params['password'] ?? '';
        $customerId = $client->findCustomerId($username);

        if (!$customerId) {
            return ['status' => 'error', 'message' => 'Kullanıcı bulunamadı.'];
        }

        $res = $client->request('PATCH', "customers/{$customerId}", ['password' => $newPass]);
        return [
            'status' => $res['status'] ? 'successful' : 'error',
            'message' => $res['status'] ? 'Şifre güncellendi.' : $res['message'],
        ];
    }

    public function ChangePackage($params = [])
    {
        $client = $this->getClient($params);
        $username = $params['user']['username'] ?? $params['username'] ?? '';
        $customerId = $client->findCustomerId($username);

        if (!$customerId) {
            return ['status' => 'error', 'message' => 'Kullanıcı bulunamadı.'];
        }

        $res = $client->request('PATCH', "customers/{$customerId}", [
            'package'     => $params['package_name'] ?? 'Web Hosting',
            'quotaMb'     => (int)($params['options']['disk_limit'] ?? 10240),
            'bandwidthMb' => (int)($params['options']['bandwidth_limit'] ?? 102400),
        ]);

        return [
            'status' => $res['status'] ? 'successful' : 'error',
            'message' => $res['status'] ? 'Paket güncellendi.' : $res['message'],
        ];
    }

    // WISECP snake_case method aliases for universal core compatibility
    public function test($options = []) { return $this->TestConnection($options); }
    public function create($params = []) { return $this->CreateAccount($params); }
    public function suspend($params = []) { return $this->SuspendAccount($params); }
    public function unsuspend($params = []) { return $this->UnsuspendAccount($params); }
    public function terminate($params = []) { return $this->TerminateAccount($params); }
    public function change_password($params = []) { return $this->ChangePassword($params); }
    public function change_plan($params = []) { return $this->ChangePackage($params); }

    public function ClientArea($params = [])
    {
        $domain   = $params['domain'] ?? '';
        $serverIp = $params['options']['server_ip'] ?? $params['server']['ip'] ?? '';
        $panelHost = !empty($domain) ? $domain : $serverIp;
        $clientPort = 8026;
        $webmailPort = 8046;

        $ssoUrl = !empty($panelHost) ? "https://{$panelHost}:{$clientPort}" : '#';
        $webmailUrl = !empty($panelHost) ? "https://{$panelHost}:{$webmailPort}" : '#';

        return [
            'template' => 'pages/clientArea.php',
            'data' => [
                'domain'        => $domain,
                'username'      => $params['user']['username'] ?? $params['username'] ?? '',
                'password'      => $params['user']['password'] ?? $params['password'] ?? '',
                'server_ip'     => $serverIp,
                'panel_host'    => $panelHost,
                'client_port'   => $clientPort,
                'webmail_port'  => $webmailPort,
                'sso_url'       => $ssoUrl,
                'webmail_url'   => $webmailUrl,
                'status'        => $params['status'] ?? 'active',
                'package_name'  => $params['package_name'] ?? 'Web Hosting',
            ],
        ];
    }
}
