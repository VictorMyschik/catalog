<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name_ru', 100)->unique();
            $table->string('name_en', 100)->unique();
            $table->char('iso3166alpha2', 3)->unique();
            $table->char('iso3166alpha3', 4)->unique();
            $table->tinyInteger('iso3166numeric')->unique();
            $table->tinyInteger('continent');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
