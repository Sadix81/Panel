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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['percentage', 'fixed']);
            $table->decimal('amount', 9, 2); // مقدار تخفیف
            $table->decimal('minimum_purchase', 9, 2)->nullable(); // حداقل مبلغ خرید
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('conditions')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->boolean('status')->nullable(); // فعال و عیر فعال کردن تخقیق
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
