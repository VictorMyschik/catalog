<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wb_catalog_good_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('good_id')->index();
            $table->unsignedBigInteger('attribute_value_id')->index();
            $table->boolean('bool_value')->nullable();

            $table->unique(['good_id', 'attribute_value_id']);

            $table->foreign('good_id')->references('id')->on('wb_catalog_goods')->cascadeOnDelete();
            $table->foreign('attribute_value_id')->references('id')->on('wb_catalog_attribute_values')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wb_catalog_good_attributes');
    }
};
