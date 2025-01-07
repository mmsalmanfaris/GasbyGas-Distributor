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
        Schema::create('crequests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->constrained('consumers')->casecadeOnDelete();
            $table->foreignId('outlet_id')->constrained('outlets')->casecadeOnDelete();
            $table->integer('quantity');
            $table->enum('panel', ['fhalf', 'shalf']);
            $table->enum('payment', ['recived', 'pending'])->default('pending');
            $table->enum('cylinder', ['recived', 'pending'])->default('pending');
            $table->date('edelivery');
            $table->date('sdelivery')->nullable();
            $table->enum('deliverystatus', ['delivered', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crequests');
    }
};
