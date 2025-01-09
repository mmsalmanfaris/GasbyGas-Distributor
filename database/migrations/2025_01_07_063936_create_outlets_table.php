<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('outlet_managements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->casecadeOnDelete();
            $table->string('name');
            $table->string('district');
            $table->string('town')->unique();
            $table->integer('stock')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlet_managements');
    }
};
