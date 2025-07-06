<?php

namespace App\Http\Controllers;

use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\CategoryEloquent as Category;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\TagEloquent as Tag;
use Illuminate\Http\Request;

class PetsController extends Controller
{
    public function index()
    {
        return view('pets.index');
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        $statuses = PetStatusEnum::values();

        return view('pets.details.create', compact('categories', 'tags', 'statuses'));
    }

    public function edit($id)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $statuses = PetStatusEnum::values();

        return view('pets.details.edit', compact('categories', 'tags', 'statuses', 'id'));
    }
}
