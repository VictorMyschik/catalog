<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wb_catalog_goods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('market_id')->index();
            $table->unsignedBigInteger('nm_id')->index();
            $table->unsignedBigInteger('imt_id')->index();
            $table->unsignedBigInteger('subject_id')->index();
            $table->string('vendor_code');
            $table->unsignedBigInteger('brand_id')->index();
            $table->string('title')->index();
            $table->string('description', 8000)->nullable();

            $table->timestampTz('downloaded_at');
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();

            $table->unique(['market_id', 'imt_id', 'nm_id']);
            $table->foreign('market_id')->references('id')->on('markets');
            $table->foreign('subject_id')->references('id')->on('wb_catalog_groups');
            $table->foreign('brand_id')->references('id')->on('wb_catalog_brands');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wb_catalog_goods');
    }
};
