<?php
/**
 * WISECP v3 zPanel Hosting & Reseller - Client Area View
 * Reference: https://github.com/wisecp-v3/sample-server-module
 * Port Mimarisi: 8026 (Client), 8036 (Root/Bayi), 8046 (Webmail), 8056 (API)
 */
defined('CORE_FOLDER') OR exit('You can not access this file.');

$domain      = $data['domain'] ?? '';
$serverIp    = $data['server_ip'] ?? '';
$panelHost   = $data['panel_host'] ?? ($domain ?: $serverIp);
$username    = $data['username'] ?? '';
$password    = $data['password'] ?? '';
$panelPort   = $data['panel_port'] ?? 8026;
$webmailPort = $data['webmail_port'] ?? 8046;
$ssoUrl      = $data['sso_url'] ?? '#';
$webmailUrl  = $data['webmail_url'] ?? '#';
$isReseller  = !empty($data['is_reseller']);
$package     = $data['package_name'] ?? 'Web Hosting';
$status      = $data['status'] ?? 'active';
?>

<link rel="stylesheet" href="coremio/modules/Servers/ZPanelHosting/assets/css/client.css">

<div class="zp-wisecp-hosting">
  <!-- Üst Bilgi Kartı -->
  <div class="zp-card">
    <div class="zp-header-flex">
      <div>
        <div class="zp-title-wrap">
          <h2 class="zp-main-title"><?php echo htmlspecialchars($domain ?: $serverIp, ENT_QUOTES, 'UTF-8'); ?></h2>
          <span class="zp-ver-badge">zPanel v1.0</span>
        </div>
        <div class="zp-meta-sub">
          Sunucu IP: <strong class="zp-code-bold"><?php echo htmlspecialchars($serverIp ?: 'Atanmadı', ENT_QUOTES, 'UTF-8'); ?></strong>
          &bull;
          Paket: <strong class="zp-strong"><?php echo htmlspecialchars($package, ENT_QUOTES, 'UTF-8'); ?></strong>
        </div>
      </div>
      <div>
        <a href="<?php echo htmlspecialchars($ssoUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="zp-btn-login">
          zPanel Giriş (:<?php echo (int)$panelPort; ?>) &rarr;
        </a>
      </div>
    </div>
  </div>

  <!-- 1. Erişim Bilgileri -->
  <div class="zp-card">
    <h3 class="zp-section-title">Hesap Giriş &amp; Erişim Bilgileri</h3>
    
    <div class="zp-info-panel">
      <div class="zp-info-row">
        <div class="zp-info-cell">
          <div class="zp-cell-label">ALAN ADI</div>
          <div class="zp-cell-val"><?php echo htmlspecialchars($domain ?: '-', ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
        <div class="zp-info-cell">
          <div class="zp-cell-label">SUNUCU IP ADRESİ</div>
          <div class="zp-cell-val"><?php echo htmlspecialchars($serverIp ?: '-', ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
      </div>

      <div class="zp-info-row">
        <div class="zp-info-cell">
          <div class="zp-cell-label">KULLANICI ADI (FTP / PANEL)</div>
          <div class="zp-cell-val"><?php echo htmlspecialchars($username ?: '-', ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
        <div class="zp-info-cell">
          <div class="zp-cell-label">HESAP ŞİFRESİ</div>
          <div class="zp-cell-val">
            <span id="zp-hosting-pwd">••••••••••••</span>
            <?php if (!empty($password)): ?>
              <button type="button" class="zp-btn-toggle" onclick="var el=document.getElementById('zp-hosting-pwd'); el.innerText=(el.innerText==='••••••••••••'?'<?php echo addslashes($password); ?>':'••••••••••••'); this.innerText=(this.innerText==='Göster'?'Gizle':'Göster');">Göster</button>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="zp-info-row">
        <div class="zp-info-cell">
          <div class="zp-cell-label">PANEL PORTU</div>
          <div>
            <a href="https://<?php echo htmlspecialchars($panelHost, ENT_QUOTES, 'UTF-8'); ?>:<?php echo (int)$panelPort; ?>" target="_blank" class="zp-link-bold">
              https://<?php echo htmlspecialchars($panelHost, ENT_QUOTES, 'UTF-8'); ?>:<?php echo (int)$panelPort; ?>
            </a>
          </div>
        </div>
        <div class="zp-info-cell">
          <div class="zp-cell-label">WEBMAIL PORTU</div>
          <div>
            <a href="https://<?php echo htmlspecialchars($panelHost, ENT_QUOTES, 'UTF-8'); ?>:<?php echo (int)$webmailPort; ?>" target="_blank" class="zp-link-bold">
              https://<?php echo htmlspecialchars($panelHost, ENT_QUOTES, 'UTF-8'); ?>:<?php echo (int)$webmailPort; ?>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Hızlı Servis Erişimi -->
  <div class="zp-card">
    <h3 class="zp-section-title">Hızlı Servis Erişimi</h3>
    
    <div class="zp-btn-group">
      <a href="<?php echo htmlspecialchars($ssoUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="zp-btn-action-outline">
        <span>&#x1F4C1;</span> Dosya Yöneticisi
      </a>

      <a href="https://<?php echo htmlspecialchars($panelHost, ENT_QUOTES, 'UTF-8'); ?>:<?php echo (int)$webmailPort; ?>" target="_blank" class="zp-btn-action-outline">
        <span>&#x2709;</span> Webmail Gelen Kutusu (:<?php echo (int)$webmailPort; ?>)
      </a>

      <a href="<?php echo htmlspecialchars($ssoUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="zp-btn-action-outline">
        <span>&#x1F5C3;</span> Veritabanları (MariaDB)
      </a>
    </div>
  </div>

  <?php if ($isReseller): ?>
  <!-- 3. Bayi (Reseller) Yönetim Bölümü -->
  <div class="zp-card">
    <div class="zp-reseller-bar">
      <h3 class="zp-section-title">Bayi (Reseller) Yönetim Paneli</h3>
      <a href="https://<?php echo htmlspecialchars($panelHost, ENT_QUOTES, 'UTF-8'); ?>:8036" target="_blank" class="zp-btn-dark">
        Bayi Paneline Git (:8036) &rarr;
      </a>
    </div>
    <p class="zp-desc-p">
      Alt müşterileriniz için yeni web barındırma hesapları tanımlayabilir, disk/trafik kotalarını belirleyebilir ve hesapları zPanel Bayi Portu (8036) üzerinden doğrudan yönetebilirsiniz.
    </p>
  </div>
  <?php endif; ?>
</div>
