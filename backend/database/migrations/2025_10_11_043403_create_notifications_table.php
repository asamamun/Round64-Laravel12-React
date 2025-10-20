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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['order', 'promotion', 'system', 'review', 'product']);
            $table->boolean('read')->default(false);
            $table->json('data')->nullable()->comment('Additional notification data');
            $table->timestamps();

            $table->index('user_id', 'idx_notifications_user_id');
            $table->index('read', 'idx_notifications_read');
            $table->index('type', 'idx_notifications_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
