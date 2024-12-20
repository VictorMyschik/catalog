<?php

use App\Models\Catalog\Wildberries\WBCatalogAttribute;
use App\Models\Catalog\Wildberries\WBCatalogBrand;
use App\Models\Catalog\Wildberries\WBCatalogGood;
use App\Models\Catalog\Wildberries\WBCatalogGroup;
use App\Models\Catalog\Wildberries\WBCatalogImage;
use App\Models\Catalog\Wildberries\WBCatalogReferenceAttribute;
use App\Models\Catalog\Wildberries\WBCountry;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create(WBCountry::getTableName(), function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('wb_id')->index();
            $table->string('name')->index();
            $table->string('full_name');
            $table->timestampTz('created_at')->useCurrent();

            $table->unique('wb_id');
        });

        Schema::create(WBCatalogBrand::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();
        });

        Schema::create(WBCatalogGroup::getTableName(), function (Blueprint $table): void {
            $table->id();
            $table->string('name')->index();
            $table->unsignedBigInteger('parent_id')->index()->nullable();
            $table->timestampTz('created_at')->useCurrent();

            $table->foreign('parent_id')->references('id')->on(WBCatalogGroup::getTableName())->cascadeOnDelete();
        });

        Schema::create(WBCatalogGood::getTableName(), function (Blueprint $table) {
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
            $table->foreign('subject_id')->references('id')->on(WBCatalogGroup::getTableName());
            $table->foreign('brand_id')->references('id')->on(WBCatalogBrand::getTableName());
        });

        Schema::create(WBCatalogImage::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('good_id')->index();
            $table->string('original_name');
            $table->string('file_name');
            $table->tinyInteger('type');
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('good_id')->references('id')->on(WBCatalogGood::getTableName());
        });

        Schema::create(WBCatalogReferenceAttribute::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique()->index();
        });

        Schema::create(WBCatalogAttribute::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_id')->index();
            $table->unsignedBigInteger('catalog_group_id')->index();
            $table->unsignedTinyInteger('type')->index(); // WBCatalogAttributeGroupEnum

            $table->foreign('catalog_group_id')->references('id')->on(WBCatalogGroup::getTableName())->cascadeOnDelete();
            $table->foreign('attribute_id')->references('id')->on(WBCatalogReferenceAttribute::getTableName())->cascadeOnDelete();
        });

      /*  Schema::create(WBCatalogAttributeValue::getTablename(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('catalog_attribute_id')->index();
            $table->string('text_value', 8000)->nullable()->index();

            $table->unique(['catalog_attribute_id', 'text_value']);

            $table->foreign('catalog_attribute_id')->references('id')->on('wb_catalog_attributes')->cascadeOnDelete();
        });

        Schema::create('wb_catalog_good_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('good_id')->index();
            $table->unsignedBigInteger('attribute_value_id')->index();
            $table->boolean('bool_value')->nullable();

            $table->unique(['good_id', 'attribute_value_id']);

            $table->foreign('good_id')->references('id')->on('wb_catalog_goods')->cascadeOnDelete();
            $table->foreign('attribute_value_id')->references('id')->on('wb_catalog_attribute_values')->cascadeOnDelete();
        });*/
    }

    public function down(): void {}
};
