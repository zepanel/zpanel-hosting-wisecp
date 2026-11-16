<?php
/**
 * zPanel Web Hosting Server Module for WISECP (v3 & v5 Full Compatibility)
 * Port Mimarisi: 8026 (Client), 8036 (Root/Bayi), 8046 (Webmail), 8056 (API)
 */

if (!class_exists('ServerModule')) {
    abstract class ServerModule {
        public $_name = '';
        public $error = '';
        public $order = [];
        public $server = [];
        public $options = [];
        public $lang = [];
        public function __construct($server = [], $options = []) {
            $this->server = $server;
            $this->options = $options;
        }
    }
}

require_once __DIR__ . '/lib/ApiClient.php';
use Coremio\Modules\Servers\ZPanelHosting\Lib\ApiClient;

class ZPanelHosting extends ServerModule
{
    public function __construct($server = [], $options = [])
    {
        $this->_name = 'ZPanelHosting';
        parent::__construct($server, $options);
    }

    private function getClient(array $params = []): ApiClient
    {
        $server = $params['server'] ?? $this->server ?? [];
        $host   = $server['ip'] ?? $server['hostname'] ?? $params['domain'] ?? '';
        $apiKey = $server['password'] ?? $server['access_hash'] ?? $server['api_token'] ?? '';
        $port   = (int)($server['api_port'] ?? 8056);
        return new ApiClient($host, $apiKey, $port ?: 8056);
    }

    // ==========================================
    // 1. BAĞLANTI TESTİ (Test Connection)
    // ==========================================
    public function TestConnection($options = [])
    {
        $host   = $options['ip'] ?? $options['hostname'] ?? '';
        $apiKey = $options['api_key'] ?? $options['password'] ?? $options['access_hash'] ?? '';
        $port   = (int)($options['api_port'] ?? 8056);
        $client = new ApiClient($host, $apiKey, $port ?: 8056);

        $res = $client->testConnection();
        if ($res['status']) {
            return ['status' => 'successful', 'message' => "zPanel Hosting API bağlantısı başarılı ({$host}:{$port})."];
        }
        $this->error = $res['message'] ?: 'Bağlantı kurulamadı.';
        return ['status' => 'error', 'message' => $this->error];
    }

    public function testConnect($options = [])
    {
        $res = $this->TestConnection($options ?: $this->server);
        return $res['status'] === 'successful';
    }

    public function test_connect(): bool
    {
        $res = $this->TestConnection($this->server);
        return $res['status'] === 'successful';
    }

    public function test($options = [])
    {
        return $this->TestConnection($options);
    }

    // ==========================================
    // 2. HESAP AÇMA (Create Account)
    // ==========================================
    public function CreateAccount($params = [])
    {
        $client   = $this->getClient($params);
        $username = $params['user']['username'] ?? $params['username'] ?? '';
        $password = $params['user']['password'] ?? $params['password'] ?? '';
        $domain   = $params['domain'] ?? '';
        $email    = $params['user']['email'] ?? "{$username}@{$domain}";
        $fullName = trim(($params['user']['name'] ?? '') . ' ' . ($params['user']['surname'] ?? ''));

        if (empty($username) || empty($password)) {
            $this->error = 'Kullanıcı adı ve parola zorunludur.';
            return ['status' => 'error', 'message' => $this->error];
        }

        // 1. Müşteri Hesabı Oluştur
        $custRes = $client->request('POST', 'customers', [
            'username'    => $username,
            'password'    => $password,
            'displayName' => $fullName ?: $username,
            'email'       => $email,
        ]);

        if (!$custRes['status']) {
            $this->error = $custRes['message'] ?: 'Kullanıcı hesabı açılamadı.';
            return ['status' => 'error', 'message' => $this->error];
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
            'status'   => 'successful',
            'message'  => 'Web hosting hesabı ve alan adı başarıyla oluşturuldu.',
            'username' => $username,
            'password' => $password,
            'ftp_info' => [
                'host' => 'ftp.' . $domain,
                'port' => 21,
                'user' => $username,
                'pass' => $password,
            ],
            'config'   => [
                'user' => $username,
                'id'   => $customerId,
            ],
        ];
    }

    public function create($domain = '', $order_options = [])
    {
        $params = is_array($domain) ? $domain : [
            'domain'   => $domain,
            'username' => $order_options['username'] ?? '',
            'password' => $order_options['password'] ?? '',
            'user'     => $order_options,
            'options'  => $order_options,
        ];
        $res = $this->CreateAccount($params);
        if ($res['status'] !== 'successful') {
            $this->error = $res['message'] ?? 'Hesap oluşturulamadı.';
            return false;
        }
        return [
            'username' => $res['username'] ?? '',
            'password' => $res['password'] ?? '',
            'ftp_info' => $res['ftp_info'] ?? [],
        ];
    }

    // ==========================================
    // 3. ASKIYA ALMA & KALDIRMA (Suspend / Unsuspend)
    // ==========================================
    public function SuspendAccount($params = [])
    {
        $client     = $this->getClient($params);
        $username   = $params['user']['username'] ?? $params['username'] ?? '';
        $customerId = $client->findCustomerId($username);

        if (!$customerId) {
            return ['status' => 'successful', 'message' => 'Hesap zaten mevcut değil.'];
        }

        $res = $client->request('PATCH', "customers/{$customerId}", ['status' => 'suspended']);
        if (!$res['status']) {
            $this->error = $res['message'] ?: 'Hesap askıya alınamadı.';
        }
        return [
            'status'  => $res['status'] ? 'successful' : 'error',
            'message' => $res['status'] ? 'Hesap askıya alındı.' : $this->error,
        ];
    }

    public function suspend($params = [])
    {
        $res = $this->SuspendAccount($params ?: $this->order);
        return $res['status'] === 'successful';
    }

    public function UnsuspendAccount($params = [])
    {
        $client     = $this->getClient($params);
        $username   = $params['user']['username'] ?? $params['username'] ?? '';
        $customerId = $client->findCustomerId($username);

        if (!$customerId) {
            $this->error = 'Hesap bulunamadı.';
            return ['status' => 'error', 'message' => $this->error];
        }

        $res = $client->request('PATCH', "customers/{$customerId}", ['status' => 'active']);
        if (!$res['status']) {
            $this->error = $res['message'] ?: 'Hesap aktif edilemedi.';
        }
        return [
            'status'  => $res['status'] ? 'successful' : 'error',
            'message' => $res['status'] ? 'Hesap aktif edildi.' : $this->error,
        ];
    }

    public function unsuspend($params = [])
    {
        $res = $this->UnsuspendAccount($params ?: $this->order);
        return $res['status'] === 'successful';
    }

    // ==========================================
    // 4. HESAP SİLME (Terminate / Cancel)
    // ==========================================
    public function TerminateAccount($params = [])
    {
        $client     = $this->getClient($params);
        $username   = $params['user']['username'] ?? $params['username'] ?? '';
        $customerId = $client->findCustomerId($username);

        if (!$customerId) {
            return ['status' => 'successful', 'message' => 'Hesap zaten silinmiş.'];
        }

        $res = $client->request('DELETE', "customers/{$customerId}");
        if (!$res['status']) {
            $this->error = $res['message'] ?: 'Hesap silinemedi.';
        }
        return [
            'status'  => $res['status'] ? 'successful' : 'error',
            'message' => $res['status'] ? 'Hesap silindi.' : $this->error,
        ];
    }

    public function terminate($params = [])
    {
        $res = $this->TerminateAccount($params ?: $this->order);
        return $res['status'] === 'successful';
    }

    public function cancel(): bool
    {
        $res = $this->TerminateAccount($this->order);
        return $res['status'] === 'successful';
    }

    public function renew(): bool
    {
        return true;
    }

    // ==========================================
    // 5. ŞİFRE DEĞİŞTİRME (Change Password)
    // ==========================================
    public function ChangePassword($params = [])
    {
        $client     = $this->getClient($params);
        $username   = $params['user']['username'] ?? $params['username'] ?? '';
        $newPass    = $params['password'] ?? '';
        $customerId = $client->findCustomerId($username);

        if (!$customerId) {
            $this->error = 'Kullanıcı bulunamadı.';
            return ['status' => 'error', 'message' => $this->error];
        }

        $res = $client->request('PATCH', "customers/{$customerId}", ['password' => $newPass]);
        if (!$res['status']) {
            $this->error = $res['message'] ?: 'Şifre güncellenemedi.';
        }
        return [
            'status'  => $res['status'] ? 'successful' : 'error',
            'message' => $res['status'] ? 'Şifre güncellendi.' : $this->error,
        ];
    }

    public function change_password($password = '')
    {
        $params = is_array($password) ? $password : ['password' => $password];
        $res = $this->ChangePassword($params);
        return $res['status'] === 'successful';
    }

    // ==========================================
    // 6. PAKET YÜKSELTME (Change Package / Upgrade)
    // ==========================================
    public function ChangePackage($params = [])
    {
        $client     = $this->getClient($params);
        $username   = $params['user']['username'] ?? $params['username'] ?? '';
        $customerId = $client->findCustomerId($username);

        if (!$customerId) {
            $this->error = 'Kullanıcı bulunamadı.';
            return ['status' => 'error', 'message' => $this->error];
        }

        $res = $client->request('PATCH', "customers/{$customerId}", [
            'package'     => $params['package_name'] ?? 'Web Hosting',
            'quotaMb'     => (int)($params['options']['disk_limit_mb'] ?? $params['options']['disk_limit'] ?? 10240),
            'bandwidthMb' => (int)($params['options']['traffic_limit_mb'] ?? $params['options']['bandwidth_limit'] ?? 102400),
        ]);

        if (!$res['status']) {
            $this->error = $res['message'] ?: 'Paket güncellenemedi.';
        }
        return [
            'status'  => $res['status'] ? 'successful' : 'error',
            'message' => $res['status'] ? 'Paket güncellendi.' : $this->error,
        ];
    }

    public function change_plan($params = [])
    {
        return $this->ChangePackage($params);
    }

    public function apply_updowngrade($orderopt = [], $product = [])
    {
        $params = array_merge($orderopt, $product);
        $res = $this->ChangePackage($params);
        return $res['status'] === 'successful';
    }

    public function upgrade(array $new_product = []): bool
    {
        $res = $this->ChangePackage($new_product);
        return $res['status'] === 'successful';
    }

    // ==========================================
    // 7. KULLANIM & METRİK RAPORLAMA (Usage & Metrics)
    // ==========================================
    public function getDisk()
    {
        return [
            'type'  => 'percentage',
            'used'  => 512,
            'limit' => 10240,
        ];
    }

    public function getBandwidth()
    {
        return [
            'type'  => 'percentage',
            'used'  => 1024,
            'limit' => 102400,
        ];
    }

    public function metrics_usage(): array
    {
        return [
            'disk_space' => [
                'used'  => 512,
                'limit' => 10240,
                'unit'  => 'MB',
            ],
            'bandwidth' => [
                'used'  => 1024,
                'limit' => 102400,
                'unit'  => 'MB',
            ],
        ];
    }

    // ==========================================
    // 8. TEK TIKLA GİRİŞ (Single Sign-On - SSO)
    // ==========================================
    public function use_clientArea_SingleSignOn(): string
    {
        $serverIp  = $this->server['ip'] ?? '127.0.0.1';
        $domain    = $this->order['options']['domain'] ?? $serverIp;
        $panelHost = !empty($domain) ? $domain : $serverIp;
        return "https://{$panelHost}:8026";
    }

    public function use_clientArea_webMail(): string
    {
        $serverIp  = $this->server['ip'] ?? '127.0.0.1';
        $domain    = $this->order['options']['domain'] ?? $serverIp;
        $panelHost = !empty($domain) ? $domain : $serverIp;
        return "https://{$panelHost}:8046";
    }

    public function use_adminArea_SingleSignOn(): string
    {
        $serverIp  = $this->server['ip'] ?? '127.0.0.1';
        return "https://{$serverIp}:8036";
    }

    public function sso_panel_login(): string
    {
        return $this->use_clientArea_SingleSignOn();
    }

    // ==========================================
    // 9. ÖZEL AKSİYON BUTONLARI (Buttons)
    // ==========================================
    public function clientArea_buttons(): array
    {
        $ssoUrl     = $this->use_clientArea_SingleSignOn();
        $webmailUrl = $this->use_clientArea_webMail();

        return [
            'zpanel_login' => [
                'text'   => 'zPanel Kontrol Paneli (:8026)',
                'type'   => 'link',
                'url'    => $ssoUrl,
                'target' => '_blank',
                'class'  => 'btn btn-primary',
            ],
            'webmail_login' => [
                'text'   => 'Webmail (:8046)',
                'type'   => 'link',
                'url'    => $webmailUrl,
                'target' => '_blank',
                'class'  => 'btn btn-info',
            ],
        ];
    }

    public function adminArea_buttons(): array
    {
        $adminSsoUrl = $this->use_adminArea_SingleSignOn();

        return [
            'zpanel_root' => [
                'text'   => 'zPanel Root / Yönetici Girişi (:8036)',
                'type'   => 'link',
                'url'    => $adminSsoUrl,
                'target' => '_blank',
                'class'  => 'btn btn-danger',
            ],
        ];
    }

    // ==========================================
    // 10. MÜŞTERİ PANEL ALANI (Client Area)
    // ==========================================
    public function ClientArea($params = [])
    {
        $domain      = $params['domain'] ?? '';
        $serverIp    = $params['options']['server_ip'] ?? $params['server']['ip'] ?? '';
        $panelHost   = !empty($domain) ? $domain : $serverIp;
        $clientPort  = 8026;
        $webmailPort = 8046;

        $ssoUrl     = !empty($panelHost) ? "https://{$panelHost}:{$clientPort}" : '#';
        $webmailUrl = !empty($panelHost) ? "https://{$panelHost}:{$webmailPort}" : '#';

        return [
            'template' => 'pages/clientArea.php',
            'data'     => [
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

    // ==========================================
    // 11. FORM & HİZMET ALANLARI (Service & Admin Fields)
    // ==========================================
    public function config_options($data = [])
    {
        return include __DIR__ . '/config.php';
    }

    public function product_configuration(array $data = []): array
    {
        return $this->config_options($data);
    }

    public function adminArea_service_fields()
    {
        return [
            'template' => 'pages/order-detail.php',
        ];
    }

    public function save_adminArea_service_fields($data = [])
    {
        return true;
    }
}

// WISECP 3 global namespace alias standard
class ZPanelHosting_Module extends ZPanelHosting {}
