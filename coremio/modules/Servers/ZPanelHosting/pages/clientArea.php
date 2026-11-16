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

<div class="zp-wisecp-hosting" style="font-family: inherit;">
  <!-- Üst Bilgi Kartı -->
  <div class="zp-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px; margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
          <h2 style="font-size: 19px; font-weight: 700; margin: 0; color: #0f172a;"><?php echo htmlspecialchars($domain ?: $serverIp); ?></h2>
          <span style="background: #e0f2fe; color: #0369a1; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 9999px;">zPanel v1.0</span>
        </div>
        <div style="font-size: 13px; color: #64748b;">
          Sunucu IP: <strong style="font-family: monospace; color: #0f172a;"><?php echo htmlspecialchars($serverIp ?: 'Atanmadı'); ?></strong>
          &bull;
          Paket: <strong style="color: #0f172a;"><?php echo htmlspecialchars($package); ?></strong>
        </div>
      </div>
      <div>
        <a href="<?php echo htmlspecialchars($ssoUrl); ?>" target="_blank" style="background: #2563eb; color: #fff; text-decoration: none; font-weight: 600; padding: 9px 18px; border-radius: 8px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
          zPanel Giriş (:<?php echo $panelPort; ?>) &rarr;
        </a>
      </div>
    </div>
  </div>

  <!-- 1. Erişim Bilgileri -->
  <div class="zp-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px; margin-bottom: 20px;">
    <h3 style="font-size: 15px; font-weight: 700; margin: 0 0 16px 0; color: #1e293b;">Hesap Giriş &amp; Erişim Bilgileri</h3>
    
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px 22px;">
      <div style="display: flex; border-bottom: 1px solid #e2e8f0; padding-bottom: 14px; margin-bottom: 14px; flex-wrap: wrap; gap: 16px;">
        <div style="flex: 1; min-width: 200px;">
          <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">ALAN ADI</div>
          <div style="font-weight: 700; font-family: ui-monospace, monospace; font-size: 14.5px; color: #0f172a;"><?php echo htmlspecialchars($domain ?: '-'); ?></div>
        </div>
        <div style="flex: 1; min-width: 200px;">
          <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">SUNUCU IP ADRESİ</div>
          <div style="font-weight: 700; font-family: ui-monospace, monospace; font-size: 14.5px; color: #0f172a;"><?php echo htmlspecialchars($serverIp ?: '-'); ?></div>
        </div>
      </div>

      <div style="display: flex; border-bottom: 1px solid #e2e8f0; padding-bottom: 14px; margin-bottom: 14px; flex-wrap: wrap; gap: 16px;">
        <div style="flex: 1; min-width: 200px;">
          <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">KULLANICI ADI (FTP / PANEL)</div>
          <div style="font-weight: 700; font-family: ui-monospace, monospace; font-size: 14.5px; color: #0f172a;"><?php echo htmlspecialchars($username ?: '-'); ?></div>
        </div>
        <div style="flex: 1; min-width: 200px;">
          <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">HESAP ŞİFRESİ</div>
          <div style="font-weight: 700; font-family: ui-monospace, monospace; font-size: 14.5px; color: #0f172a;">
            <span id="zp-hosting-pwd">••••••••••••</span>
            <?php if (!empty($password)): ?>
              <button type="button" style="border: none; background: #e2e8f0; color: #334155; font-size: 11.5px; font-weight: 600; padding: 2px 8px; border-radius: 4px; cursor: pointer; margin-left: 8px;" onclick="var el=document.getElementById('zp-hosting-pwd'); el.innerText=(el.innerText==='••••••••••••'?'<?php echo addslashes($password); ?>':'••••••••••••'); this.innerText=(this.innerText==='Göster'?'Gizle':'Göster');">Göster</button>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div style="display: flex; flex-wrap: wrap; gap: 16px;">
        <div style="flex: 1; min-width: 200px;">
          <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">PANEL PORTU</div>
          <div style="font-size: 13.5px; font-family: ui-monospace, monospace;">
            <a href="https://<?php echo htmlspecialchars($panelHost); ?>:<?php echo $panelPort; ?>" target="_blank" style="color: #2563eb; text-decoration: none; font-weight: 600;">
              https://<?php echo htmlspecialchars($panelHost); ?>:<?php echo $panelPort; ?>
            </a>
          </div>
        </div>
        <div style="flex: 1; min-width: 200px;">
          <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">WEBMAIL PORTU</div>
          <div style="font-size: 13.5px; font-family: ui-monospace, monospace;">
            <a href="https://<?php echo htmlspecialchars($panelHost); ?>:<?php echo $webmailPort; ?>" target="_blank" style="color: #2563eb; text-decoration: none; font-weight: 600;">
              https://<?php echo htmlspecialchars($panelHost); ?>:<?php echo $webmailPort; ?>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Hızlı Servis Erişimi -->
  <div class="zp-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px; margin-bottom: 20px;">
    <h3 style="font-size: 15px; font-weight: 700; margin: 0 0 14px 0; color: #1e293b;">Hızlı Servis Erişimi</h3>
    
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
      <a href="<?php echo htmlspecialchars($ssoUrl); ?>" target="_blank" style="background: #f8fafc; border: 1px solid #cbd5e1; color: #0f172a; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 8px;">
        <span>&#x1F4C1;</span> Dosya Yöneticisi
      </a>

      <a href="https://<?php echo htmlspecialchars($panelHost); ?>:<?php echo $webmailPort; ?>" target="_blank" style="background: #f8fafc; border: 1px solid #cbd5e1; color: #0f172a; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 8px;">
        <span>&#x2709;</span> Webmail Gelen Kutusu (:<?php echo $webmailPort; ?>)
      </a>

      <a href="<?php echo htmlspecialchars($ssoUrl); ?>" target="_blank" style="background: #f8fafc; border: 1px solid #cbd5e1; color: #0f172a; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 8px;">
        <span>&#x1F5C3;</span> Veritabanları (MariaDB)
      </a>
    </div>
  </div>

  <?php if ($isReseller): ?>
  <!-- 3. Bayi (Reseller) Yönetim Bölümü -->
  <div class="zp-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
      <h3 style="font-size: 15px; font-weight: 700; margin: 0; color: #1e293b;">Bayi (Reseller) Yönetim Paneli</h3>
      <a href="https://<?php echo htmlspecialchars($panelHost); ?>:8036" target="_blank" style="background: #0f172a; color: #ffffff; padding: 8px 18px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600;">
        Bayi Paneline Git (:8036) &rarr;
      </a>
    </div>
    <p style="font-size: 13.5px; color: #64748b; margin: 0; line-height: 1.5;">
      Müşterilerinize ait web alanlarını, veritabanlarını ve kotalarını zPanel Bayi Portu (8036) üzerinden kolayca oluşturabilir ve yönetebilirsiniz.
    </p>
  </div>
  <?php endif; ?>
</div>
