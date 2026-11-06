# 🏭 Eiston Paketleme Makineleri - Web Sitesi & Yönetim Paneli

Eiston Paketleme Makineleri resmi web sitesi, modern kurumsal tasarımı, 17 ürünlük makine kataloğu ve güvenlikli yönetim paneli.

---

## 🚀 Özellikler

* **Tasarım:** Modern, ultra-hızlı ve %100 responsive (mobil, tablet, masaüstü uyumlu).
* **Kurumsal Kimlik:** Vektörel SVG amblem ve logo, geçişli (dark/light) header desteği.
* **Ürün Kataloğu:** 4 kategori, 17 ürün modeli (L Kesim Shrink, Poşet Ağzı Kapama, Skinpack Vakum, Polietilen Shrink).
* **Otomatik Google Haritalar:** Adres değiştirildiğinde Google Harita pini otomatik olarak yeni adrese güncellenir.
* **Yönetim Paneli:** Firma bilgileri, telefon/WhatsApp, logo ve ürün ekleme/düzenleme/silme.
* **Güvenlik Sistemi:**
  * Gizli yönetim URL'si (Standart `/admin` yolu 404 honeypot ile korunur).
  * Brute-Force engeli (5 hatalı denemede 15 dk kilit).
  * CSRF token doğrulaması.
  * XSS ve reklam enjeksiyonu engelleme filtreleri.
  * Güvenli dosya yükleme (MIME kontrolü, kod taraması, rastgele dosya adlandırma).
  * Veritabanı dizin koruması (`.htaccess`).

---

## 🛠️ Kurulum & Çalıştırma

### 1. Localhost (XAMPP / Wamp)
1. Projeyi web sunucusu kök dizinine kopyalayın (örn: `c:/xampp/htdocs/eistonpaketleme`).
2. Tarayıcıda açın:
   - Ana Sayfa: `http://localhost/eistonpaketleme/`
   - Ürünler: `http://localhost/eistonpaketleme/urunler.php`
   - Yönetim Paneli: `http://localhost/eistonpaketleme/eiston-yonetim/login.php`

### 2. Veritabanı Seçenekleri
- **SQLite (Varsayılan):** Sıfır kurulum gerektirir, gömülü çalışır (`eiston-yonetim/data/eiston.db`).
- **MySQL / phpMyAdmin:** `eiston_veritabani.sql` dosyasını phpMyAdmin üzerinden içe aktarın. `eiston-yonetim/includes/db.php` dosyasındaki veritabanı bilgilerini ayarlayın.

---

## 🔑 Yönetim Paneli Giriş

- **Giriş Yolu:** `/eiston-yonetim/login.php`
- **Kullanıcı Adı:** `eiston_admin`
- **Şifre:** `Eiston2024!Guvenli` *(Panel içerisinden değiştirilebilir)*

---

&copy; 2024 Eiston Paketleme Makineleri. Tüm hakları saklıdır.
