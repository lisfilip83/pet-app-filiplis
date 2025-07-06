<?php

namespace App\Modules\Pet\src\Infrastructure\Persistence\Models;

use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;
use Database\Factories\PetEloquentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PetEloquent extends Model
{
    /** @use HasFactory<PetEloquentFactory> */
    use HasFactory;

    protected static string $factory = PetEloquentFactory::class;

    protected $table = 'pets';
    protected $fillable = [
        'name',
        'status',
        'category_id',
        'photo_urls',
    ];

    protected $casts = [
        'status' => PetStatusEnum::class,
        'photo_urls' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryEloquent::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(TagEloquent::class, 'pet_tag', 'pet_id', 'tag_id');
    }

    public function addPhotoUrl(string $url): void
    {
        $photoUrls = $this->photo_urls ?? [];
        $photoUrls[] = $url;
        $this->photo_urls = $photoUrls;
    }

}
