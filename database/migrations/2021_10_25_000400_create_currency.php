<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('currency', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->index();
            $table->string('text_code', 3)->index();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->string('name', 200)->index();
            $table->tinyInteger('rounding');
            $table->string('description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currency');
    }
};
