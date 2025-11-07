<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido_productos', function (Blueprint $table) {
            $table->decimal('precio', 10, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('pedido_productos', function (Blueprint $table) {
            $table->decimal('precio', 10, 2)->nullable(false)->default(null)->change();
        });
    }
};
