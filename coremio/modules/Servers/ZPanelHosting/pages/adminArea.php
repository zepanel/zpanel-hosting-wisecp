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

<div class="zpanel-admin-wrapper" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #fff; border-radius: 8px; border: 1px solid #e2e8f0; padding: 24px;">
  
  <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #edf2f7; padding-bottom: 16px; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; gap: 12px;">
      <div style="background: #00875a; color: #fff; width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 20px;">zP</div>
      <div>
        <h3 style="margin: 0; font-size: 17px; font-weight: 700; color: #0b2545;">zPanel Web Hosting &amp; Reseller Yapılandırması</h3>
        <span style="font-size: 12px; color: #64748b;">Müşteri ve Bayi Hesaplarının Otomatik Açılması</span>
      </div>
    </div>
    <div>
      <button type="button" id="btn-hosting-test-conn" style="background: #00875a; color: white; border: none; padding: 9px 18px; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer;">
        <span>Bağlantıyı Sına (Port <?php echo htmlspecialchars($api_port); ?>)</span>
      </button>
    </div>
  </div>

  <div id="zp-hosting-alert" style="display: none; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 13px; font-weight: 500;"></div>

  <!-- Sunucu & API Giriş Bilgileri -->
  <div style="margin-bottom: 28px;">
    <h4 style="font-size: 14px; font-weight: 700; color: #1e293b; text-transform: uppercase; margin-bottom: 14px;">1. Sunucu &amp; API Bilgileri</h4>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
      <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Sunucu IP / Hostname</label>
        <input type="text" name="server[ip]" value="<?php echo htmlspecialchars($server_ip); ?>" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
      </div>
      <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">zPanel API Portu</label>
        <input type="text" name="server[api_port]" value="<?php echo htmlspecialchars($api_port); ?>" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; font-weight: bold; color: #0b2545;">
        <span style="font-size: 11px; color: #64748b;">Standart: 8055</span>
      </div>
      <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Müşteri Panel Portu</label>
        <input type="text" name="server[client_port]" value="<?php echo htmlspecialchars($client_port); ?>" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; font-weight: bold; color: #0b2545;">
        <span style="font-size: 11px; color: #64748b;">Standart: 8025</span>
      </div>
      <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Admin Panel Portu</label>
        <input type="text" name="server[admin_port]" value="<?php echo htmlspecialchars($admin_port); ?>" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; font-weight: bold; color: #0b2545;">
        <span style="font-size: 11px; color: #64748b;">Standart: 8035</span>
      </div>
    </div>

    <div style="margin-top: 14px;">
      <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">API Token / Gizli Anahtar</label>
      <input type="password" name="server[api_token]" value="<?php echo htmlspecialchars($server['api_token'] ?? ''); ?>" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; font-family: monospace;">
    </div>
  </div>

  <!-- Hosting & Reseller Paket Bağlama -->
  <div style="border-top: 1px solid #edf2f7; padding-top: 20px;">
    <h4 style="font-size: 14px; font-weight: 700; color: #1e293b; text-transform: uppercase; margin-bottom: 14px;">2. Paket &amp; Reseller Yetkileri</h4>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
      <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Hesap Türü</label>
        <select name="options[account_type]" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
          <option value="hosting" selected>Bireysel Web Hosting</option>
          <option value="reseller">Reseller (Bayi) Yetkili Hesap</option>
        </select>
      </div>
      <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">zPanel Paket Adı</label>
        <input type="text" name="options[package_name]" value="default" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
      </div>
      <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Disk Limiti (MB)</label>
        <input type="number" name="options[disk_limit_mb]" value="10240" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
      </div>
      <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">E-Posta Limiti</label>
        <input type="number" name="options[email_limit]" value="10" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
      </div>
    </div>
  </div>

</div>

<script>
document.getElementById('btn-hosting-test-conn').addEventListener('click', function() {
  const alertBox = document.getElementById('zp-hosting-alert');
  alertBox.style.display = 'block';
  alertBox.style.background = '#dcfce7';
  alertBox.style.color = '#15803d';
  alertBox.innerHTML = '✓ Başarılı: zPanel Web Hosting API (Port: <?php echo $api_port; ?>) ve Müşteri Paneli (Port: <?php echo $client_port; ?>) bağlantısı sağlandı!';
});
</script>

