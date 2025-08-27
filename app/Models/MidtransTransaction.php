<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MidtransTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'snap_token',
        'siswa_id',
        'tagihan_id',
        'gross_amount',
        'transaction_status',
        'payment_type',
        'transaction_id',
        'fraud_status',
        'bank',
        'va_number',
        'midtrans_response',
        'transaction_time',
        'settlement_time'
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'gross_amount' => 'decimal:2',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    public function isSuccess()
    {
        return in_array($this->transaction_status, ['capture', 'settlement']);
    }

    public function isPending()
    {
        return $this->transaction_status === 'pending';
    }

    public function isFailed()
    {
        return in_array($this->transaction_status, ['cancel', 'deny', 'expire', 'failure']);
    }
}
