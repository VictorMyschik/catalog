<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('catalog_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index();
            $table->jsonb('sl')->nullable();
            $table->string('json_link')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_types');
    }
};
