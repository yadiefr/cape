# 🔧 HOSTING STORAGE DEBUGGING GUIDE

Berdasarkan debug info hosting Anda, berikut adalah panduan langkah demi langkah untuk mengatasi masalah storage upload:

## 📊 Hasil Analisis Debug

✅ **Yang Sudah Benar:**
- Hosting environment terdeteksi
- Semua direktori required sudah ada
- Path structure sudah sesuai

❌ **Masalah yang Ditemukan:**
- File logo sekolah tidak ada di lokasi manapun
- Upload process kemungkinan gagal tanpa error yang jelas

## 🎯 LANGKAH TROUBLESHOOTING

### Step 1: Test Upload Functionality
1. Buka **Storage Sync** page di admin panel
2. Scroll ke bagian **"Test Upload (Debugging)"**
3. Upload file gambar kecil (max 1MB)
4. Klik **"Test Upload"**
5. Lihat hasil:
   - ✅ **primary_exists**: File ada di `/home/smkpgric/cape/storage/app/public/test/`
   - ✅ **secondary_exists**: File ada di `/home/smkpgric/project_laravel/storage/app/public/test/`
   - ✅ **public_exists**: File ada di `/home/smkpgric/public_html/storage/test/`

### Step 2: Jika Test Upload Berhasil
Berarti sistem baru sudah bekerja. Sekarang:
1. Hapus logo lama dari database (jika ada)
2. Upload logo sekolah baru melalui **Settings** page
3. Check debug info lagi untuk memastikan file ada

### Step 3: Jika Test Upload Gagal
Check log Laravel di hosting:
- Path: `/home/smkpgric/cape/storage/logs/laravel.log`
- Cari error terbaru
- Kemungkinan masalah:
  - **Permission denied**: Directory tidak writable
  - **Disk full**: Storage hosting penuh
  - **PHP limits**: File size atau execution time limit

## 🔥 IMMEDIATE FIX - Manual Upload

Jika sistem masih belum bekerja, lakukan manual:

### Via File Manager cPanel:
1. **Upload logo ke:**
   - `/home/smkpgric/cape/storage/app/public/settings/`
   - `/home/smkpgric/public_html/storage/settings/`

2. **Rename file sesuai pattern:**
   - `logo_sekolah_[timestamp]_[random].png`
   - Example: `logo_sekolah_1756282413_68aebe2d.png`

3. **Update database:**
   ```sql
   UPDATE settings 
   SET value = 'settings/logo_sekolah_1756282413_68aebe2d.png' 
   WHERE key = 'logo_sekolah';
   ```

4. **Set permissions:**
   - File: 644
   - Directory: 755

### Via SSH (jika tersedia):
```bash
# Navigate to storage
cd /home/smkpgric/cape/storage/app/public/settings/

# Check current files
ls -la

# Copy to public_html if exists
cp * /home/smkpgric/public_html/storage/settings/

# Fix permissions
chmod 644 /home/smkpgric/public_html/storage/settings/*
```

## 🚨 EMERGENCY WORKAROUND

Jika semua gagal, edit file `.env` hosting:
```
FILESYSTEM_DISK=public
STORAGE_PATH=/home/smkpgric/public_html/storage
```

Dan update `config/filesystems.php`:
```php
'public' => [
    'driver' => 'local',
    'root' => env('STORAGE_PATH', storage_path('app/public')),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

## 📞 Next Steps

1. **Test** upload functionality with debug tool
2. **Monitor** Laravel logs for any errors
3. **Use** storage sync tool if files need manual sync
4. **Contact** hosting support if permission issues persist

## 💡 Prevention

Untuk mencegah masalah di masa depan:
1. Regular backup storage files
2. Monitor disk usage hosting
3. Test upload setelah update sistem
4. Use storage sync tool secara berkala

---

**Status:** Ready untuk testing di hosting dengan enhanced upload system dan debug tools! 🚀
