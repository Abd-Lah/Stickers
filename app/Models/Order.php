<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where(string $string, string $string1)
 * @method static orderBy(string $string, string $string1)
 * @method static findOrfail($id)
 */
class Order extends Model
{
    //
    use HasFactory;
    protected $fillable = [
      'name',
      'email',
      'phone',
      'address',
      'city',
      'status',
      'payment_method',
      'payment_status',
      'total_price',
    ];

    public function orderItems() : HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
