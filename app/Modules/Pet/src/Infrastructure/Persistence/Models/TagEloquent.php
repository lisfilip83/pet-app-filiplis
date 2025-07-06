<?php

namespace App\Modules\Pet\src\Infrastructure\Persistence\Models;

use Database\Factories\TagEloquentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TagEloquent extends Model
{
    /** @use HasFactory<TagEloquentFactory> */
    use HasFactory;

    protected static string $factory = TagEloquentFactory::class;

    public $timestamps = false;
    protected $table = 'tags';
    protected $fillable = [
        'name',
    ];

    public function pets(): BelongsToMany
    {
        return $this->belongsToMany(PetEloquent::class, 'pet_tag', 'tag_id', 'pet_id');
    }
}
