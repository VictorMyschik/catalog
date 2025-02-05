<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Таблица наименований групп атрибутов, таких как Общие, Дополнительно, Железо
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('catalog_group_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_id');
            $table->string('name', 100);
            $table->integer('sort')->default(0);

            $table->foreign('type_id')->references('id')->on('catalog_types')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_group_attributes');
    }
};
