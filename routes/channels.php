<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\RuangUjian;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Channel untuk monitoring ruang ujian (Admin only)
Broadcast::channel('exam-room.{roomId}', function ($user, $roomId) {
    // Hanya admin yang bisa mengakses channel ini
    if (auth()->guard('admin')->check()) {
        $admin = auth()->guard('admin')->user();
        return true;
    }
    
    // Siswa yang terdaftar di ruang ujian ini juga bisa mengakses
    if (auth()->guard('siswa')->check()) {
        $siswa = auth()->guard('siswa')->user();
        $ruangUjian = RuangUjian::find($roomId);
        
        if ($ruangUjian && $ruangUjian->siswa->contains('id', $siswa->id)) {
            return ['id' => $siswa->id, 'name' => $siswa->nama];
        }
    }
    
    return false;
});
