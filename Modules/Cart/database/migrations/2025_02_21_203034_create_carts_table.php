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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('total_price', 10, 2)->default(0)->nullable();
            $table->decimal('discounted_price', 10, 2)->default(0)->nullable();
            $table->decimal('final_price', 10, 2)->default(0)->nullable();
            $table->string('uuid', 36)->nullable(); // فیلد برای ذخیره UUID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
