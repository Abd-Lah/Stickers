<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    //
    use HasFactory;
    protected $fillable = [
      'order_id',
      'sticker_id',
      'quantity',
      'sub_price'
    ];

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function sticker() : BelongsTo
    {
        return $this->belongsTo(Sticker::class);
    }
}
