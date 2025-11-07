<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pedido_productos', function (Blueprint $table) {
            $table->renameColumn('precio', 'precio_unitario');
        });
    }

    public function down(): void
    {
        Schema::table('pedido_productos', function (Blueprint $table) {
            $table->renameColumn('precio_unitario', 'precio');
        });
    }
};
