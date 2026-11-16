<?php
/**
 * WISECP v3 zPanel Hosting & Reseller - Client Area View
 * Reference: https://github.com/wisecp-v3/sample-server-module
 * 
 * Dinamik Değerler: Müşterinin alan adı ($domain), sunucu IP'si ($server_ip) ve zPanel resmi portları (8026 / 8036 / 8046).
 */
defined('CORE_FOLDER') OR exit('You can not access this file.');

$domain      = $data['domain'] ?? '';
$serverIp    = $data['server_ip'] ?? '';
$panelHost   = $data['panel_host'] ?? ($domain ?: $serverIp);
$username    = $data['username'] ?? '';
$password    = $data['password'] ?? '';
$panelPort   = $data['panel_port'] ?? 8026;
$webmailPort = $data['webmail_port'] ?? 8046;
$ssoLink     = $data['sso_link'] ?? '#';
$webmailLink = $data['webmail_link'] ?? '#';
$isReseller  = !empty($data['is_reseller']);
$package     = $data['package'] ?? 'Web Hosting';
$status      = $data['status'] ?? 'Active';
?>

<!-- Modül Kendi Varlıkları (Assets) -->
<link rel="stylesheet" href="coremio/modules/Servers/ZPanelHosting/assets/css/client.css">
<script src="coremio/modules/Servers/ZPanelHosting/assets/js/client.js"></script>

<div class="zp-wisecp-hosting">
  <!-- Üst Bilgi Kartı -->
  <div class="zp-card">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h2 class="h5 fw-bold mb-1 text-dark"><?php echo htmlspecialchars($domain ?: $serverIp); ?></h2>
        <p class="text-muted small mb-0 font-monospace">
          Sunucu IP: <strong><?php echo htmlspecialchars($serverIp ?: 'Belirtilmedi'); ?></strong> &bull; Paket: <?php echo htmlspecialchars($package); ?>
        </p>
      </div>
      <div>
        <a href="<?php echo htmlspecialchars($ssoLink); ?>" target="_blank" class="btn btn-success fw-bold px-4 py-2 rounded-pill">
          zPanel Paneline Giriş (:<?php echo $panelPort; ?>) &rarr;
        </a>
      </div>
    </div>
  </div>

  <!-- 1. Erişim Bilgileri -->
  <div class="zp-card">
    <h3 class="h6 fw-bold mb-3 text-dark">Hesap Giriş &amp; Erişim Bilgileri</h3>
    <div class="zp-box-info">
      <div class="row g-3 pb-3 border-bottom">
        <div class="col-sm-6">
          <div class="zp-label-caps">ALAN ADI</div>
          <div class="zp-val-mono"><?php echo htmlspecialchars($domain ?: '-'); ?></div>
        </div>
        <div class="col-sm-6">
          <div class="zp-label-caps">SUNUCU IP ADRESİ</div>
          <div class="zp-val-mono"><?php echo htmlspecialchars($serverIp ?: '-'); ?></div>
        </div>
      </div>
      <div class="row g-3 pt-3 pb-3 border-bottom">
        <div class="col-sm-6">
          <div class="zp-label-caps">FTP / KULLANICI ADI</div>
          <div class="zp-val-mono"><?php echo htmlspecialchars($username ?: '-'); ?></div>
        </div>
        <div class="col-sm-6">
          <div class="zp-label-caps">FTP / PANEL PAROLASI</div>
          <div class="zp-val-mono">
            <span id="zp-hosting-pwd">••••••••••</span>
            <?php if (!empty($password)): ?>
              <button class="btn btn-sm btn-link text-decoration-none p-0 ms-2" onclick="toggleHostingPassword('zp-hosting-pwd', '<?php echo addslashes($password); ?>')">Göster</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="row g-3 pt-3">
        <div class="col-sm-6">
          <div class="zp-label-caps">PANEL GİRİŞ ADRESİ</div>
          <div class="small font-monospace">
            <?php if (!empty($panelHost)): ?>
              <a href="https://<?php echo htmlspecialchars($panelHost); ?>:<?php echo $panelPort; ?>" target="_blank">
                https://<?php echo htmlspecialchars($panelHost); ?>:<?php echo $panelPort; ?>
              </a>
            <?php else: ?>
              <span class="text-muted">Kurulum bekleniyor...</span>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="zp-label-caps">WEBMAIL GİRİŞ ADRESİ</div>
          <div class="small font-monospace">
            <?php if (!empty($panelHost)): ?>
              <a href="https://<?php echo htmlspecialchars($panelHost); ?>:<?php echo $webmailPort; ?>" target="_blank">
                https://<?php echo htmlspecialchars($panelHost); ?>:<?php echo $webmailPort; ?>
              </a>
            <?php else: ?>
              <span class="text-muted">Kurulum bekleniyor...</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. E-Posta Yönetimi -->
  <div class="zp-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="h6 fw-bold mb-0 text-dark">E-Posta Yönetimi (Hesap Oluşturma)</h3>
      <a href="<?php echo htmlspecialchars($webmailLink); ?>" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3">
        Webmail'e Git (:<?php echo $webmailPort; ?>)
      </a>
    </div>

    <div class="p-3 bg-light rounded-3 border">
      <form onsubmit="handleCreateEmailAccount(event, '<?php echo htmlspecialchars($domain); ?>');" class="row g-2">
        <div class="col-md-5">
          <div class="input-group input-group-sm">
            <input type="text" id="zp-email-account-user" class="form-control" placeholder="adsoyad" required>
            <span class="input-group-text bg-white">@<?php echo htmlspecialchars($domain ?: 'domain'); ?></span>
          </div>
        </div>
        <div class="col-md-4">
          <input type="password" id="zp-email-account-pass" class="form-control form-control-sm" placeholder="Parola" required>
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-dark btn-sm w-100">E-Posta Oluştur</button>
        </div>
      </form>
    </div>
  </div>

  <?php if ($isReseller): ?>
  <!-- 3. Reseller (Bayi) Paneli -->
  <div class="zp-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="h6 fw-bold mb-0 text-dark">Bayi Yönetimi (Alt Müşteri Hesapları)</h3>
      <a href="https://<?php echo htmlspecialchars($panelHost); ?>:8036" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
        Bayi Yönetici Paneli (:8036) &rarr;
      </a>
    </div>
    <p class="text-muted small">Müşterilerinize ait web alanlarını, veritabanlarını ve kotalarını zPanel Bayi Portu (8036) üzerinden kolayca oluşturabilir ve yönetebilirsiniz.</p>
  </div>
  <?php endif; ?>
</div>
