<?php
/**
 * zPanel Web Hosting - Aktivasyon E-postası (HTML)
 */
defined('CORE_FOLDER') OR exit('You can not access this file.');
$domain      = $options['domain'] ?? ($order['domain'] ?? '');
$username    = $options['config']['user'] ?? ($options['user'] ?? '');
$password    = $options['config']['password'] ?? '********';
$serverIp    = $order['server']['ip'] ?? ($options['server_ip'] ?? '');
$clientUrl   = !empty($domain) ? "https://{$domain}:8026" : "https://{$serverIp}:8026";
$webmailUrl  = !empty($domain) ? "https://{$domain}:8046" : "https://{$serverIp}:8046";
?>
<link rel="stylesheet" href="coremio/modules/Servers/ZPanelHosting/assets/css/client.css">

<div class="zp-mail-wrap">
    <h2>Web Hosting Hizmetiniz Aktif Edildi!</h2>
    <p>Merhaba,</p>
    <p>zPanel tabanlı web hosting hesabınız başarıyla oluşturulmuştur. Erişim bilgileriniz aşağıda yer almaktadır:</p>
    
    <table class="zp-table">
        <tr class="zp-row-alt">
            <td class="zp-cell-bold">Alan Adı:</td>
            <td><?php echo htmlspecialchars($domain, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr>
            <td class="zp-cell-bold">Sunucu IP:</td>
            <td><?php echo htmlspecialchars($serverIp, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr class="zp-row-alt">
            <td class="zp-cell-bold">Kullanıcı Adı:</td>
            <td class="zp-code-bold"><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr>
            <td class="zp-cell-bold">Şifre:</td>
            <td class="zp-code-bold"><?php echo htmlspecialchars($password, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr class="zp-row-alt">
            <td class="zp-cell-bold">zPanel Giriş Portu:</td>
            <td><a href="<?php echo htmlspecialchars($clientUrl, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($clientUrl, ENT_QUOTES, 'UTF-8'); ?></a></td>
        </tr>
        <tr>
            <td class="zp-cell-bold">Webmail Portu:</td>
            <td><a href="<?php echo htmlspecialchars($webmailUrl, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($webmailUrl, ENT_QUOTES, 'UTF-8'); ?></a></td>
        </tr>
        <tr class="zp-row-alt">
            <td class="zp-cell-bold">FTP Sunucusu:</td>
            <td>ftp.<?php echo htmlspecialchars($domain, ENT_QUOTES, 'UTF-8'); ?> (Port: 21)</td>
        </tr>
    </table>
    
    <p>Saygılarımızla,<br>Hosting &amp; Sunucu Destek Ekibi</p>
</div>
