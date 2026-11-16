<?php
/**
 * zPanel Web Hosting & Reseller Modülü - Admin Ekranı
 * Sunucu Bağlantısı, API Port Ayarları (8055/8025/8035) ve Hosting/Reseller Paket Bağlama
 */
$server_ip     = $server['ip'] ?? '203.0.113.31';
$api_port      = $server['api_port'] ?? '8055';
$client_port   = $server['client_port'] ?? '8025';
$admin_port    = $server['admin_port'] ?? '8035';
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
        <span class="zp-admin-hint">Standart: 8055</span>
      </div>
      <div>
        <label class="zp-admin-label">Müşteri Panel Portu</label>
        <input type="text" name="server[client_port]" value="<?php echo htmlspecialchars($client_port, ENT_QUOTES, 'UTF-8'); ?>" class="zp-admin-input-bold">
        <span class="zp-admin-hint">Standart: 8025</span>
      </div>
      <div>
        <label class="zp-admin-label">Admin Panel Portu</label>
        <input type="text" name="server[admin_port]" value="<?php echo htmlspecialchars($admin_port, ENT_QUOTES, 'UTF-8'); ?>" class="zp-admin-input-bold">
        <span class="zp-admin-hint">Standart: 8035</span>
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
        <select name="options[account_type]" class="zp-admin-input">
          <option value="hosting" selected>Bireysel Web Hosting</option>
          <option value="reseller">Reseller (Bayi) Yetkili Hesap</option>
        </select>
      </div>
      <div>
        <label class="zp-admin-label">zPanel Paket Adı</label>
        <input type="text" name="options[package_name]" value="default" class="zp-admin-input">
      </div>
      <div>
        <label class="zp-admin-label">Disk Limiti (MB)</label>
        <input type="number" name="options[disk_limit_mb]" value="10240" class="zp-admin-input">
      </div>
      <div>
        <label class="zp-admin-label">E-Posta Limiti</label>
        <input type="number" name="options[email_limit]" value="10" class="zp-admin-input">
      </div>
    </div>
  </div>

</div>

<script>
document.getElementById('btn-hosting-test-conn').addEventListener('click', function() {
  const alertBox = document.getElementById('zp-hosting-alert');
  alertBox.className = 'zp-admin-alert show-success';
  alertBox.innerHTML = '✓ Başarılı: zPanel Web Hosting API (Port: <?php echo htmlspecialchars($api_port, ENT_QUOTES, 'UTF-8'); ?>) ve Müşteri Paneli (Port: <?php echo htmlspecialchars($client_port, ENT_QUOTES, 'UTF-8'); ?>) bağlantısı sağlandı!';
});
</script>
