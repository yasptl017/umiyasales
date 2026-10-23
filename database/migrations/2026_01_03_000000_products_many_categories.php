<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_product', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->primary(['product_id', 'category_id']);
        });

        // keep existing assignments
        DB::table('products')->select('id', 'category_id')->orderBy('id')->each(function ($p) {
            DB::table('category_product')->insert(['product_id' => $p->id, 'category_id' => $p->category_id]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
        });
        DB::table('category_product')->orderBy('product_id')->each(function ($r) {
            DB::table('products')->where('id', $r->product_id)->whereNull('category_id')->update(['category_id' => $r->category_id]);
        });
        Schema::dropIfExists('category_product');
    }
};
