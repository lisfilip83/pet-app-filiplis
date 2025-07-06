<?php

namespace App\Modules\Pet\src\Infrastructure\Persistence\Repositories;

use App\Modules\Pet\src\Domain\Aggregates\Pet;
use App\Modules\Pet\src\Domain\Data\Collections\PetCollection;
use App\Modules\Pet\src\Domain\Data\Collections\PetStatusCollection;
use App\Modules\Pet\src\Domain\Data\ValueObjects\PetId;
use App\Modules\Pet\src\Domain\Data\ValueObjects\PetPhotoUrl;
use App\Modules\Pet\src\Domain\Repositories\PetRepository;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\PetEloquent;

final readonly class PetRepositoryEloquent implements PetRepository
{
    public function __construct(
        private PetEloquent $model
    ) {
    }

    public function findById(PetId $id): ?Pet
    {
        $pet = $this->model
            ->with(['category', 'tags'])
            ->find($id->value());

        return $pet ? Pet::fromArray($pet->toArray()) : null;
    }

    public function store(Pet $pet): Pet
    {
        $model = $this->model->query()->create([
            'name' => $pet->name,
            'status' => $pet->status,
            'category_id' => $pet->category->id,
            'photo_urls' => $pet->photoUrls->toArray(),
        ]);

        $tagIds = $pet->tags->pluck('id')->all();
        $model->tags()->sync($tagIds);

        return $pet->createWithId($model->id);
    }

    public function update(Pet $pet): ?Pet
    {
        $model = $this->model->query()->find($pet->id->value());
        if (!$model) {
            return null;
        }

        $saved = $model->update([
            'name' => $pet->name,
            'status' => $pet->status,
            'category_id' => $pet->category->id,
            'photo_urls' => $pet->photoUrls->toArray(),
        ]);

        $tagIds = $pet->tags->pluck('id')->all();
        $model->tags()->sync($tagIds);

        return $saved ? $pet : null;
    }

    public function delete(PetId $id): bool
    {
        return $this->model->query()->where('id', $id->value())->delete();
    }


    public function findByStatus(PetStatusCollection $statuses): PetCollection
    {
        $pets = $this->model->query()
            ->with(['category', 'tags'])
            ->whereIn('status', $statuses->toArrayOfValues())
            ->get()
            ->transform(
                fn (PetEloquent $model) => Pet::fromArray($model->toArray())
            );

        return PetCollection::create($pets);
    }

    public function addImageUrl(PetId $id, PetPhotoUrl $url): bool
    {
        $model = $this->model->query()->find($id->value());
        if (!$model) {
            return false;
        }

        $model->addPhotoUrl($url->value());

        return $model->save();
    }
}
