<?php

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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('rating')->comment('Rating from 1 to 5');
            $table->string('title');
            $table->text('comment');
            $table->json('images')->nullable()->comment('Array of review image URLs');
            $table->boolean('verified')->default(false);
            $table->unsignedInteger('helpful_count')->default(0);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();

            $table->index('product_id', 'idx_reviews_product_id');
            $table->index('user_id', 'idx_reviews_user_id');
            $table->index('rating', 'idx_reviews_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
