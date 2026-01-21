<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public const STATUS_TRADING = 'trading';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'item_id',
        'buyer_id',
        'seller_id',
        'status',
        'completed_at',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function isTrading(): bool
    {
        return $this->status === self::STATUS_TRADING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function messages()
    {
        return $this->hasMany(Message::class , 'transaction_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'transaction_id');
    }

    protected $casts = [
        'completed_at' => 'datetime',
    ];

}
