<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('product_url')->nullable()->change();
            $table->decimal('mrp', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('product_url')->nullable(false)->change();
            $table->decimal('mrp', 10, 2)->nullable(false)->change();
        });
    }
};
