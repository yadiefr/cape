# SMK Website - Hosting Setup Guide

## Masalah Logo/File Upload di Hosting

### Masalah
File logo yang diupload tersimpan di `/home/user/project_laravel/storage/app/public/settings/` tapi yang dibaca website adalah dari `/home/user/public_html/storage/settings/`

### Solusi

#### 1. Automatic Sync (Recommended)
Jalankan command Laravel untuk sinkronisasi otomatis:

```bash
cd /home/user/project_laravel
php artisan storage:sync --force
```

#### 2. Manual Script
Jalankan script bash yang disediakan:

```bash
cd /home/user/project_laravel
chmod +x sync-storage.sh
./sync-storage.sh
```

#### 3. Symbolic Link (Jika didukung hosting)
Buat symbolic link dari public_html ke storage:

```bash
cd /home/user/public_html
rm -rf storage  # Hapus jika ada
ln -s /home/user/project_laravel/storage/app/public storage
```

#### 4. Cron Job (Otomatisasi)
Tambahkan ke crontab untuk sync otomatis setiap 5 menit:

```bash
crontab -e
```

Tambahkan line:
```
*/5 * * * * cd /home/user/project_laravel && php artisan storage:sync >/dev/null 2>&1
```

### File-file yang Ditambahkan

1. **SettingsHelper.php** - Updated dengan fungsi sync_storage_file_to_public()
2. **SyncStorageFiles Command** - php artisan storage:sync
3. **AutoSyncStorage Middleware** - Auto-sync saat file 404
4. **sync-storage.sh** - Script bash untuk hosting

### Verifikasi

Setelah menjalankan solusi di atas:

1. Cek apakah file ada:
   ```bash
   ls -la /home/user/public_html/storage/settings/
   ```

2. Cek permission:
   ```bash
   find /home/user/public_html/storage -type f -exec ls -la {} \;
   ```

3. Test akses URL:
   ```
   https://yourdomain.com/storage/settings/logo_xxxxx.png
   ```

### Troubleshooting

#### File tidak muncul setelah sync:
- Cek permission file (harus 644)
- Cek permission directory (harus 755)
- Cek .htaccess di public_html/storage

#### Permission Error:
```bash
find /home/user/public_html/storage -type f -exec chmod 644 {} \;
find /home/user/public_html/storage -type d -exec chmod 755 {} \;
```

#### .htaccess untuk storage directory:
Buat file `/home/user/public_html/storage/.htaccess`:
```
# Allow image files
<FilesMatch "\.(jpg|jpeg|png|gif|webp|svg)$">
    Order allow,deny
    Allow from all
</FilesMatch>

# Deny access to other files
<FilesMatch "^(?!.*\.(jpg|jpeg|png|gif|webp|svg)$).*$">
    Order deny,allow
    Deny from all
</FilesMatch>
```

## Auto-Update Settings Cache

Sistem juga memiliki auto-clear cache saat setting diupdate untuk memastikan perubahan logo langsung terlihat.
