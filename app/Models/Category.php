<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $array)
 * @method static findOrFail($id)
 * @method static where(string $string, $slug)
 * @method static orderBy(string $string, string $string1)
 */
class Category extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image'
    ];


    public function stickers() : HasMany
    {
        return $this->hasMany(Sticker::class);
    }
}
