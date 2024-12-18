<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wb_catalog_images', function (Blueprint $table): void {
            $table->id();
            $table->unsignedTinyInteger('media_type')->index(); // MediaTypeEnum
            $table->unsignedBigInteger('good_id')->index();
            $table->string('hash', 32)->index();
            $table->string('path');
            $table->unsignedBigInteger('size');
            $table->string('extension')->nullable();

            $table->timestampTz('created_at')->useCurrent();

            $table->foreign('good_id')->references('id')->on('wb_catalog_goods')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wb_catalog_images');
    }
};
