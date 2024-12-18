<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wb_catalog_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_id')->index();
            $table->unsignedBigInteger('catalog_group_id')->index();
            $table->unsignedTinyInteger('type')->index(); // WBCatalogAttributeGroupEnum

            $table->foreign('catalog_group_id')->references('id')->on('wb_catalog_groups')->cascadeOnDelete();
            $table->foreign('attribute_id')->references('id')->on('wb_catalog_reference_attributes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wb_catalog_attributes');
    }
};
