# zPanel Web Hosting & Reseller Modülü API Dokümantasyonu (WISECP)

zPanel Hosting modülü, zPanel Core REST API v1 ile çalışır. Web sunucusu olarak **zMaxServer (OpenLiteSpeed & LSPHP / LSAPI)** entegre edilmiştir.

---

## 1. Port Mimarisi

- **8026 / TCP (HTTPS):** Müşteri Web Kontrol Paneli
- **8036 / TCP (HTTPS):** Bayi (Reseller) ve Yönetici Paneli
- **8046 / TCP (HTTPS):** Webmail Servisi
- **8056 / TCP (HTTPS):** Core REST API (WISECP Sunucu Otomasyonu)

---

## 2. API Yetkilendirme

API istekleri, sunucu ekleme ekranında girilen `api_token` değeri ile Bearer token olarak iletilir:

```http
Authorization: Bearer <api_token>
Content-Type: application/json
Accept: application/json
```

---

## 3. Desteklenen WISECP Metotları ve API Uçları

| WISECP Metodu | HTTP Yöntemi | zPanel Core API Uç Noktası | Açıklama |
|---|---|---|---|
| `TestConnection` / `test` | `GET` | `/v1/health` | API ve port bağlantı denetimi |
| `CreateAccount` / `create` | `POST` | `/v1/customers` & `/v1/domains` | Hesap ve ilk alan adı açılışı |
| `SuspendAccount` / `suspend` | `PATCH` | `/v1/customers/:id` | Hesabı askıya alma (`status: suspended`) |
| `UnsuspendAccount` / `unsuspend` | `PATCH` | `/v1/customers/:id` | Hesabı aktif etme (`status: active`) |
| `TerminateAccount` / `terminate` | `DELETE` | `/v1/customers/:id` | Hesabı kalıcı olarak silme |
| `ChangePassword` / `change_password` | `PATCH` | `/v1/customers/:id` | Hesap şifresini güncelleme |
| `ChangePackage` / `change_plan` | `PATCH` | `/v1/customers/:id` | Paket, disk ve trafik kotası güncelleme |
| `ClientArea` / `clientArea` | Dahili | `pages/clientArea.php` | Müşteri paneli detay & tek tıkla giriş ekranı |

