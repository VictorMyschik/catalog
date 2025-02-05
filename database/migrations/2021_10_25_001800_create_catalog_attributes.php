<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('catalog_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_attribute_id')->index();
            $table->string('name', 100);
            $table->string('description', 8000)->nullable();
            $table->integer('sort')->default(0);

            $table->foreign('group_attribute_id')->references('id')->on('catalog_group_attributes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_attributes');
    }
};
