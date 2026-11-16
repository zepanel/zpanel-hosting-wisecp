<?php
/**
 * zPanel Web Hosting - Admin Sipariş Detay Formu (Order Detail)
 * WiseCP 3 ve WiseCP 5 Çekirdek Standartlarına Uygundur.
 */
defined('CORE_FOLDER') OR exit('You can not access this file.');

$LANG     = $module->lang ?? [];
$order    = isset($order) && $order ? $order : [];
$options  = isset($order['options']) ? $order['options'] : [];
$config   = isset($options['config']) ? $options['config'] : [];
$domain   = isset($options['domain']) ? $options['domain'] : ($order['domain'] ?? '');
$user     = isset($config['user']) ? $config['user'] : ($options['user'] ?? '');
$serverIp = isset($order['server']['ip']) ? $order['server']['ip'] : ($options['server_ip'] ?? '');
$adminUrl = !empty($serverIp) ? "https://{$serverIp}:8036" : '#';
$clientUrl= !empty($domain) ? "https://{$domain}:8026" : (!empty($serverIp) ? "https://{$serverIp}:8026" : '#');

$esc = function ($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
?>
<link rel="stylesheet" href="coremio/modules/Servers/ZPanelHosting/assets/css/admin.css">

<div class="zp-admin-section">
    <h4 class="zp-admin-section-heading">zPanel Web Hosting Yönetimi</h4>

    <div class="zp-admin-grid-server">
        <div>
            <label class="zp-admin-label">Alan Adı (Domain)</label>
            <input type="text" name="domain" value="<?php echo $esc($domain); ?>" class="zp-admin-input">
        </div>
        <div>
            <label class="zp-admin-label">Kullanıcı Adı (Username)</label>
            <input type="text" name="config[user]" value="<?php echo $esc($user); ?>" class="zp-admin-input-bold">
        </div>
        <div>
            <label class="zp-admin-label">Şifre (Password)</label>
            <input type="password" name="config[password]" placeholder="*******" class="zp-admin-input">
        </div>
        <div>
            <label class="zp-admin-label">Sunucu IP</label>
            <input type="text" value="<?php echo $esc($serverIp); ?>" readonly class="zp-admin-input-mono">
        </div>
    </div>

    <div class="zp-admin-row-margin">
        <a href="<?php echo $esc($adminUrl); ?>" target="_blank" class="zp-btn-test-conn">
            <span>zPanel Root / Yönetici Girişi (:8036) &rarr;</span>
        </a>
        <a href="<?php echo $esc($clientUrl); ?>" target="_blank" class="zp-btn-test-conn">
            <span>Müşteri Paneli Girişi (:8026) &rarr;</span>
        </a>
    </div>
</div>
