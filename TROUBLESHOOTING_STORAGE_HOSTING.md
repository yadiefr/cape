# Troubleshooting Storage Upload - Panduan Hosting

## Masalah: File tidak tersimpan saat upload di hosting

Jika setelah mengupload logo sekolah atau file lainnya, file tidak muncul di website, ikuti langkah-langkah berikut:

## Langkah 1: Akses Storage Sync Tool

1. Login ke admin panel
2. Klik menu **Storage Sync** di sidebar
3. Klik tombol **Debug Info** untuk melihat informasi sistem

## Langkah 2: Analisis Debug Info

Debug info akan menampilkan:

```json
{
  "environment": {
    "is_hosting": true/false,
    "base_path": "/home/user/project_laravel",
    "storage_path": "/home/user/project_laravel/storage",
    "public_path": "/home/user/project_laravel/public"
  },
  "paths": {
    "laravel_storage": "/home/user/project_laravel/storage/app/public",
    "public_storage": "/home/user/project_laravel/public/storage",
    "project_laravel_path": "/home/user/project_laravel",
    "public_html_path": "/home/user/public_html"
  },
  "directory_checks": {
    "storage_app_public_exists": true/false,
    "public_storage_exists": true/false,
    "project_laravel_exists": true/false,
    "public_html_exists": true/false
  },
  "settings_directories": {
    "storage_app_public_settings": true/false,
    "public_storage_settings": true/false,
    "project_laravel_storage_settings": true/false,
    "public_html_storage_settings": true/false
  }
}
```

## Langkah 3: Identifikasi Masalah

### Kasus A: Environment tidak terdeteksi sebagai hosting
**Gejala:** `"is_hosting": false` padahal di hosting
**Solusi:** 
1. Periksa struktur direktori hosting
2. Pastikan ada direktori `/home/user/project_laravel` dan `/home/user/public_html`

### Kasus B: Direktori tidak ada
**Gejala:** `"storage_app_public_exists": false`
**Solusi:**
1. Buat direktori secara manual melalui file manager hosting:
   - `/home/user/project_laravel/storage/app/public/`
   - `/home/user/project_laravel/storage/app/public/settings/`
   - `/home/user/public_html/storage/`
   - `/home/user/public_html/storage/settings/`

### Kasus C: Permissions error
**Gejala:** `"storage_writable": false`
**Solusi:**
1. Set permission direktori storage ke 755
2. Set permission file ke 644

## Langkah 4: Manual Fix di Hosting

Jika automatic sync tidak bekerja, lakukan manual:

### Via File Manager Hosting:
1. Buka file manager hosting
2. Navigate ke `/home/user/project_laravel/storage/app/public/settings/`
3. Copy semua file ke `/home/user/public_html/storage/settings/`
4. Set permission file ke 644

### Via cPanel File Manager:
1. Login cPanel → File Manager
2. Navigate ke `project_laravel/storage/app/public/settings/`
3. Select semua file → Copy
4. Navigate ke `public_html/storage/settings/`
5. Paste files
6. Select files → Change Permissions → 644

## Langkah 5: Test Upload Baru

1. Upload logo sekolah baru melalui admin settings
2. Check log Laravel untuk error:
   ```
   /home/user/project_laravel/storage/logs/laravel.log
   ```
3. Pastikan file muncul di kedua lokasi:
   - `/home/user/project_laravel/storage/app/public/settings/`
   - `/home/user/public_html/storage/settings/`

## Langkah 6: Gunakan Storage Sync Tool

1. Kembali ke **Storage Sync** page
2. Klik **Check Status** untuk refresh info
3. Jika ada missing files, klik **Sync Files Now**
4. Monitor hasil sync

## Common Issues & Solutions

### 1. "Class 'App\Models\Settings' not found"
**Penyebab:** Database connection error atau model tidak ter-autoload
**Solusi:**
```bash
php artisan config:cache
php artisan route:cache
composer dump-autoload
```

### 2. "SQLSTATE[HY000] [1045] Access denied"
**Penyebab:** Database credentials salah di hosting
**Solusi:** 
1. Check file `.env` di hosting
2. Update database credentials sesuai hosting
3. Test connection

### 3. File upload berhasil tapi tidak muncul
**Penyebab:** Path mismatch antara Laravel dan web public directory
**Solusi:**
1. Use Storage Sync tool untuk copy file
2. Pastikan symlink atau copy mechanism berjalan

### 4. Permission denied saat copy file
**Penyebab:** Insufficient permissions
**Solusi:**
1. Set directory permissions: `chmod 755`
2. Set file permissions: `chmod 644`
3. Check ownership: `chown user:user`

## Advanced: Custom Storage Path

Jika struktur hosting berbeda, update di `config/filesystems.php`:

```php
'public' => [
    'driver' => 'local',
    'root' => env('STORAGE_PUBLIC_PATH', storage_path('app/public')),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

Tambahkan di `.env`:
```
STORAGE_PUBLIC_PATH=/home/user/public_html/storage
```

## Monitoring & Maintenance

1. **Regular Check:** Gunakan Storage Sync tool seminggu sekali
2. **Log Monitoring:** Check `storage/logs/laravel.log` untuk errors
3. **Backup:** Backup files sebelum major changes
4. **Test Upload:** Test upload setelah perubahan hosting

---

**Tip:** Simpan dokumentasi ini untuk referensi masa depan. Jika masalah persist, hubungi support hosting dengan informasi dari Debug Info.
