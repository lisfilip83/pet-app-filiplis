<?php

namespace App\Modules\Pet\src\Infrastructure\Persistence\Models;

use Database\Factories\CategoryEloquentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryEloquent extends Model
{
    /** @use HasFactory<CategoryEloquentFactory> */
    use HasFactory;

    protected static string $factory = CategoryEloquentFactory::class;

    public $timestamps = false;
    protected $table = 'categories';
    protected $fillable = [
        'name',
    ];

    public function pets(): HasMany
    {
        return $this->hasMany(PetEloquent::class, 'category_id');
    }
}
