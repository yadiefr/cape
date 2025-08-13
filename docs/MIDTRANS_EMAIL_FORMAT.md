## Update Format Email Pelanggan Midtrans

### Perubahan yang Diterapkan:

#### 1. **Format Email Baru**
- **Sebelum**: `siswa@siswa.smk.ac.id` atau email asli siswa
- **Sesudah**: `namasiswa-kelas-nis@student.smk.ac.id`
- **Contoh**: `mohamadfadilahakbar-xiitkr1-232410045@student.smk.ac.id`

#### 2. **Method Baru di MidtransServiceNew**
```php
private function generateCustomerEmail(Siswa $siswa)
{
    // Format: "Nama Siswa - Kelas - NIS"
    $nama = $siswa->nama_lengkap ?? $siswa->nama ?? 'Student';
    $kelasName = $siswa->kelas ? $siswa->kelas->nama_kelas : 'Unknown';
    $nis = $siswa->nis ?? $siswa->nisn ?? 'Unknown';
    
    // Clean nama untuk email (hapus karakter yang tidak valid untuk email)
    $cleanNama = preg_replace('/[^a-zA-Z0-9\s]/', '', $nama);
    $cleanKelas = preg_replace('/[^a-zA-Z0-9\s]/', '', $kelasName);
    
    // Format untuk email: nama-kelas-nis@student.smk.ac.id
    $emailParts = [
        str_replace(' ', '', strtolower($cleanNama)),
        str_replace(' ', '', strtolower($cleanKelas)),
        $nis
    ];
    
    $emailFormat = implode('-', array_filter($emailParts));
    
    // Fallback jika email terlalu panjang atau ada masalah
    if (strlen($emailFormat) > 50) {
        $emailFormat = $siswa->id . '-' . $nis;
    }
    
    return $emailFormat . '@student.smk.ac.id';
}
```

#### 3. **Customer Details di Midtrans**
```php
'customer_details' => [
    'first_name' => $siswa->nama_lengkap ?? $siswa->nama ?? 'Student',
    'email' => $this->generateCustomerEmail($siswa),
    'phone' => $siswa->telepon ?? $siswa->no_telp ?? '08123456789'
],
```

#### 4. **Load Relasi Kelas**
- Menambahkan `$siswa->load('kelas')` untuk memastikan data kelas tersedia
- Menggunakan `nama_lengkap` dan `nama_kelas` sesuai struktur database

#### 5. **Format Akhir Email**
- **Nama**: MOHAMAD FADILAH AKBAR → mohamadfadilahakbar
- **Kelas**: XII TKR 1 → xiitkr1  
- **NIS**: 232410045
- **Result**: `mohamadfadilahakbar-xiitkr1-232410045@student.smk.ac.id`

#### 6. **Keuntungan Format Baru**
- ✅ **Identifikasi Mudah**: Langsung tahu nama siswa, kelas, dan NIS
- ✅ **Unique**: Setiap siswa memiliki email unik
- ✅ **Readable**: Format mudah dibaca dan dipahami
- ✅ **Clean**: Karakter khusus dihapus untuk kompatibilitas email
- ✅ **Fallback**: Ada sistem cadangan jika data tidak lengkap

#### 7. **Testing**
- Test route: `/test-email-format`
- Test transaction: `/test-midtrans-new`  
- Email akan muncul di Midtrans sandbox dengan format baru

### Screenshot Midtrans Sandbox
Sekarang di kolom "E-MAIL PELANGGAN" akan muncul format:
`namasiswa-kelas-nis@student.smk.ac.id`
