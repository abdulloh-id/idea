<?php

use App\Models\User;
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
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();

            // Set up a relationship with the Users table
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();

            // Non-nullable title, every idea must have one
            $table->string('title');

            // Nullable text description
            $table->text('description')->nullable();

            // Current state of the idea
            $table->string('status')->default('pending');

            // Featured image for the idea
            $table->string('image_path')->nullable();

            // Idea can contain links, by default the list is empty
            $table->json('links')->default('[]');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ideas');
    }
};
