<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wb_catalog_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('catalog_attribute_id')->index();
            $table->string('text_value', 8000)->nullable()->index();

            $table->unique(['catalog_attribute_id', 'text_value']);

            $table->foreign('catalog_attribute_id')->references('id')->on('wb_catalog_attributes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wb_catalog_attribute_values');
    }
};
