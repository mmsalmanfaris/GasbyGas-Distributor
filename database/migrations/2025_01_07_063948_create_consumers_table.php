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
        Schema::create('consumers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlets')->casecadeOnDelete();
            $table->string('fname');
            $table->integer('nic')->unique();
            $table->string('contact')->unique();
            $table->string('email')->unique();
            $table->string('district');
            $table->enum('category', ['family', 'industry'])->default('family');
            $table->string('rnumber')->nullable();
            $table->string('password')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumers');
    }
};
