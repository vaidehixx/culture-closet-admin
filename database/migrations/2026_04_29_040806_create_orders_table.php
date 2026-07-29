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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrower_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days');
            $table->decimal('price_per_day', 8, 2);
            $table->decimal('subtotal', 8, 2);
            $table->decimal('platform_fee', 8, 2)->default(0);
            $table->decimal('total', 8, 2);
            $table->string('promo_code')->nullable();
            $table->decimal('discount', 8, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'active', 'returned', 'completed', 'cancelled', 'disputed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
