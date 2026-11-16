<?php
/**
 * zPanel Web Hosting - Aktivasyon E-postası (Metin / Plaintext)
 */
defined('CORE_FOLDER') OR exit('You can not access this file.');
$domain      = $options['domain'] ?? ($order['domain'] ?? '');
$username    = $options['config']['user'] ?? ($options['user'] ?? '');
$password    = $options['config']['password'] ?? '********';
$serverIp    = $order['server']['ip'] ?? ($options['server_ip'] ?? '');
$clientUrl   = !empty($domain) ? "https://{$domain}:8026" : "https://{$serverIp}:8026";
$webmailUrl  = !empty($domain) ? "https://{$domain}:8046" : "https://{$serverIp}:8046";
?>
Web Hosting Hizmetiniz Aktif Edildi!

Alan Adı: <?php echo $domain; ?>

Sunucu IP: <?php echo $serverIp; ?>

Kullanıcı Adı: <?php echo $username; ?>

Şifre: <?php echo $password; ?>

zPanel Giriş Portu: <?php echo $clientUrl; ?>

Webmail Portu: <?php echo $webmailUrl; ?>

FTP Sunucusu: ftp.<?php echo $domain; ?> (Port: 21)

Saygılarımızla,
Hosting & Sunucu Destek Ekibi
