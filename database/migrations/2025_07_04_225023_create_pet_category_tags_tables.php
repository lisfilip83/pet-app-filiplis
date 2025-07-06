<?php

use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', PetStatusEnum::values());
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->json('photo_urls')->nullable();
            $table->timestamps();
        });

        Schema::create('pet_tag', function (Blueprint $table) {
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            $table->primary(['pet_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_tag');
        Schema::dropIfExists('pets');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('tags');
    }
};
