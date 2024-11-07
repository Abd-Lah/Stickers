<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $data)
 * @method static findOrfail($id)
 */
class Sticker extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'image',
        'caracteristics',
        'price',
        'discount'
    ];
    protected $casts = [
        'image' => 'array',
        'caracteristics' => 'array'
    ];

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
