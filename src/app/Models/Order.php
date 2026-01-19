<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'payment_method',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}