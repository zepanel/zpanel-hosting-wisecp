<?php
/**
 * zPanel Web Hosting & Reseller Modülü - Admin Ekranı
 * Sunucu Bağlantısı, API Port Ayarları (8056/8026/8036/8046) ve Hosting/Reseller Paket Bağlama
 */
$server_ip     = $server['ip'] ?? '203.0.113.31';
$api_port      = $server['api_port'] ?? '8056';
$client_port   = $server['client_port'] ?? '8026';
$admin_port    = $server['admin_port'] ?? '8036';
$is_secure     = !empty($server['secure']) ? 'checked' : '';
?>

<link rel="stylesheet" href="coremio/modules/Servers/ZPanelHosting/assets/css/admin.css">

<div class="zpanel-admin-wrapper">
  
  <div class="zp-admin-header">
    <div class="zp-admin-brand">
      <div class="zp-admin-logo-box">zP</div>
      <div>
        <h3 class="zp-admin-title">zPanel Web Hosting &amp; Reseller Yapılandırması</h3>
        <span class="zp-admin-subtitle">Müşteri ve Bayi Hesaplarının Otomatik Açılması</span>
      </div>
    </div>
    <div>
      <button type="button" id="btn-hosting-test-conn" class="zp-btn-test-conn">
        <span>Bağlantıyı Sına (Port <?php echo htmlspecialchars($api_port, ENT_QUOTES, 'UTF-8'); ?>)</span>
      </button>
    </div>
  </div>

  <div id="zp-hosting-alert" class="zp-admin-alert"></div>

  <!-- Sunucu & API Giriş Bilgileri -->
  <div class="zp-admin-section">
    <h4 class="zp-admin-section-heading">1. Sunucu &amp; API Bilgileri</h4>
    <div class="zp-admin-grid-server">
      <div>
        <label class="zp-admin-label">Sunucu IP / Hostname</label>
        <input type="text" name="server[ip]" value="<?php echo htmlspecialchars($server_ip, ENT_QUOTES, 'UTF-8'); ?>" class="zp-admin-input">
      </div>
      <div>
        <label class="zp-admin-label">zPanel API Portu</label>
        <input type="text" name="server[api_port]" value="<?php echo htmlspecialchars($api_port, ENT_QUOTES, 'UTF-8'); ?>" class="zp-admin-input-bold">
        <span class="zp-admin-hint">Standart: 8056</span>
      </div>
      <div>
        <label class="zp-admin-label">Müşteri Panel Portu</label>
        <input type="text" name="server[client_port]" value="<?php echo htmlspecialchars($client_port, ENT_QUOTES, 'UTF-8'); ?>" class="zp-admin-input-bold">
        <span class="zp-admin-hint">Standart: 8026</span>
      </div>
      <div>
        <label class="zp-admin-label">Admin Panel Portu</label>
        <input type="text" name="server[admin_port]" value="<?php echo htmlspecialchars($admin_port, ENT_QUOTES, 'UTF-8'); ?>" class="zp-admin-input-bold">
        <span class="zp-admin-hint">Standart: 8036</span>
      </div>
    </div>

    <div class="zp-admin-row-margin">
      <label class="zp-admin-label">API Token / Gizli Anahtar</label>
      <input type="password" name="server[api_token]" value="<?php echo htmlspecialchars($server['api_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="zp-admin-input-mono">
    </div>
  </div>

  <!-- Hosting & Reseller Paket Bağlama -->
  <div class="zp-admin-section-border">
    <h4 class="zp-admin-section-heading">2. Paket &amp; Reseller Yetkileri</h4>
    <div class="zp-admin-grid-packages">
      <div>
        <label class="zp-admin-label">Hesap Türü</label>
        <select name="options[account_type]" class="zp-admin-select">
          <option value="hosting" <?php echo (($options['account_type'] ?? '') == 'hosting') ? 'selected' : ''; ?>>Bireysel Web Hosting Hesabı</option>
          <option value="reseller" <?php echo (($options['account_type'] ?? '') == 'reseller') ? 'selected' : ''; ?>>Reseller (Bayi) Yetkili Hesap</option>
        </select>
      </div>
      <div>
        <label class="zp-admin-label">zPanel Paket Adı</label>
        <input type="text" name="options[package_name]" value="<?php echo htmlspecialchars($options['package_name'] ?? 'default', ENT_QUOTES, 'UTF-8'); ?>" class="zp-admin-input-bold">
      </div>
      <div>
        <label class="zp-admin-label">Web Disk Alanı Kotası (MB)</label>
        <input type="text" name="options[disk_limit_mb]" value="<?php echo htmlspecialchars($options['disk_limit_mb'] ?? '10240', ENT_QUOTES, 'UTF-8'); ?>" class="zp-admin-input">
        <span class="zp-admin-hint">0 = Sınırsız</span>
      </div>
      <div>
        <label class="zp-admin-label">Aylık Trafik Kotası (MB)</label>
        <input type="text" name="options[traffic_limit_mb]" value="<?php echo htmlspecialchars($options['traffic_limit_mb'] ?? '102400', ENT_QUOTES, 'UTF-8'); ?>" class="zp-admin-input">
        <span class="zp-admin-hint">0 = Sınırsız</span>
      </div>
      <div>
        <label class="zp-admin-label">E-Posta Hesap Limiti</label>
        <input type="text" name="options[email_limit]" value="<?php echo htmlspecialchars($options['email_limit'] ?? '10', ENT_QUOTES, 'UTF-8'); ?>" class="zp-admin-input">
      </div>
      <div>
        <label class="zp-admin-label">Reseller Alt Müşteri Limiti</label>
        <input type="text" name="options[reseller_sub_limit]" value="<?php echo htmlspecialchars($options['reseller_sub_limit'] ?? '25', ENT_QUOTES, 'UTF-8'); ?>" class="zp-admin-input">
      </div>
    </div>
  </div>

</div>

<script src="coremio/modules/Servers/ZPanelHosting/assets/js/client.js"></script>
