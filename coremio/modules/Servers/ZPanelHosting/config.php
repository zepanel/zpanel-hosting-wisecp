<?php
/**
 * zPanel Web Hosting & Reseller Modülü - WISECP v3 Konfigürasyonu
 * API Port: 8056, Müşteri Port: 8026, Admin Port: 8036, Webmail: 8046
 */
return [
    'type-version'      => '3.0',
    'name'              => 'ZPanelHosting',
    'readable-name'     => 'zPanel Web Hosting & Reseller Modülü',
    'status'            => true,
    'authors'           => 'zPanel Team',
    'version'           => '1.0.0',

    // Sunucu Ekleme Ekranı
    'server-fields'     => [
        'ip'            => [
            'name'        => 'Sunucu IP veya Hostname',
            'type'        => 'text',
            'description' => 'zPanel Web Hosting sunucusu (Örn: 203.0.113.31 veya web01.zepanel.com)',
        ],
        'api_port'      => [
            'name'        => 'Dış REST API Portu',
            'type'        => 'text',
            'default'     => '8056',
            'description' => 'zPanel Dış REST API portu (Resmi: 8056/TCP HTTPS)',
        ],
        'client_port'   => [
            'name'        => 'Kullanıcı Kontrol Paneli Portu',
            'type'        => 'text',
            'default'     => '8026',
            'description' => 'Müşteri Web Kontrol Paneli Portu (Resmi: 8026/TCP HTTPS)',
        ],
        'admin_port'    => [
            'name'        => 'Bayi / Root Panel Portu',
            'type'        => 'text',
            'default'     => '8036',
            'description' => 'Bayi ve Yönetici Giriş Portu (Resmi: 8036/TCP HTTPS)',
        ],
        'api_token'     => [
            'name'        => 'API Token / Gizli Anahtar',
            'type'        => 'password',
            'description' => 'zPanel API_INTERNAL_TOKEN anahtarı',
        ],
        'secure'        => [
            'name'        => 'SSL ile Bağlan (HTTPS)',
            'type'        => 'approval',
            'checked'     => true,
        ],
    ],

    // Paket Tanımlama & Bağlama Ekranı
    'options'           => [
        'account_type'  => [
            'name'        => 'Hesap Türü',
            'type'        => 'select',
            'options'     => [
                'hosting'   => 'Bireysel Web Hosting Hesabı',
                'reseller'  => 'Reseller (Bayi) Yetkili Hesap',
            ],
            'description' => 'Hesabın bayi (reseller) oluşturma yetkisi olsun mu?',
        ],
        'package_name'  => [
            'name'        => 'zPanel Paket Adı',
            'type'        => 'text',
            'default'     => 'default',
            'description' => 'zPanel panelinde tanımlı paket şablon adı (Örn: eko, pro, reseller)',
        ],
        'disk_limit_mb' => [
            'name'        => 'Disk Kotası (MB)',
            'type'        => 'text',
            'default'     => '10240',
            'description' => '0 = Sınırsız',
        ],
        'traffic_limit_mb' => [
            'name'        => 'Aylık Trafik Kotası (MB)',
            'type'        => 'text',
            'default'     => '102400',
            'description' => '0 = Sınırsız',
        ],
        'email_limit'   => [
            'name'        => 'E-Posta Hesap Limiti',
            'type'        => 'text',
            'default'     => '10',
            'description' => 'Müşterinin açabileceği maksimum e-posta sayısı',
        ],
        'reseller_sub_limit' => [
            'name'        => 'Reseller Alt Müşteri Limiti',
            'type'        => 'text',
            'default'     => '25',
            'description' => 'Reseller paketlerinde açılabilecek alt hesap sayısı',
        ],
    ],
];
