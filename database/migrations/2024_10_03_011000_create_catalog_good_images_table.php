<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wb_catalog_good_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('good_id')->index();
            $table->string('original_name');
            $table->string('file_name');
            $table->tinyInteger('type');
            $table->unsignedBigInteger('wb_changed_id')->index()->nullable();

            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('good_id')->references('id')->on('wb_catalog_goods');
            $table->foreign('wb_changed_id')->references('id')->on('wb_catalog_changed');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wb_catalog_good_images');
    }
};
