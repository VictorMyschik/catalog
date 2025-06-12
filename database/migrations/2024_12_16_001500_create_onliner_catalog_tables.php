<?php

use App\Models\Catalog\Onliner\OnCatalogAttribute;
use App\Models\Catalog\Onliner\OnCatalogAttributeValue;
use App\Models\Catalog\Onliner\OnCatalogGood;
use App\Models\Catalog\Onliner\OnCatalogGoodAttribute;
use App\Models\Catalog\Onliner\OnCatalogGroup;
use App\Models\Catalog\Onliner\OnCatalogGroupAttribute;
use App\Models\Catalog\Onliner\OnCatalogImage;
use App\Models\Catalog\Onliner\OnCatalogMarket;
use App\Models\Catalog\Onliner\OnCatalogPrice;
use App\Models\Catalog\Onliner\OnManufacturer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create(OnManufacturer::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('address', 1000)->nullable();

            $table->timestampTz('created_at')->useCurrent();
        });
        Schema::create(OnCatalogGroup::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index();
            $table->jsonb('sl')->nullable();
            $table->string('json_link')->nullable();
        });

        Schema::create(OnCatalogGroupAttribute::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->index();
            $table->string('name', 100);
            $table->integer('sort')->default(0);

            $table->foreign('group_id')->references('id')->on(OnCatalogGroup::getTableName())->cascadeOnDelete();
        });

        Schema::create(OnCatalogAttribute::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_attribute_id')->index();
            $table->string('name', 100);
            $table->string('description', 8000)->nullable();
            $table->integer('sort')->default(0);

            $table->foreign('group_attribute_id')->references('id')->on(OnCatalogGroupAttribute::getTableName())->cascadeOnDelete();
        });

        Schema::create(OnCatalogAttributeValue::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('catalog_attribute_id')->index();
            $table->string('text_value', 8000)->nullable()->index();

            $table->unique(['catalog_attribute_id', 'text_value']);

            $table->foreign('catalog_attribute_id')->references('id')->on(OnCatalogAttribute::getTableName())->cascadeOnDelete();
        });

        Schema::create(OnCatalogGood::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->index();
            $table->boolean('active')->default(false)->index();
            $table->string('prefix', 500)->nullable();
            $table->string('name', 500)->index();
            $table->string('short_info', 2000)->nullable(); //краткие сведения о товаре
            $table->string('description', 500)->nullable();    // для себя
            $table->unsignedBigInteger("manufacturer_id")->nullable()->index();
            $table->string("parent_good_id")->nullable()->index();
            $table->boolean("is_certification")->default(false);
            $table->integer('int_id')->nullable()->unique()->index();
            $table->string('string_id')->nullable()->unique()->index();
            $table->string('link')->nullable();
            $table->string('vendor_code', 50)->nullable()->unique()->index();
            $table->jsonb('sl')->nullable();

            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('group_id')->references('id')->on(OnCatalogGroup::getTableName())->cascadeOnDelete();
            $table->foreign('manufacturer_id')->references('id')->on(OnManufacturer::getTableName())->onDelete('set null');
        });

        Schema::create(OnCatalogGoodAttribute::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('good_id')->index();
            $table->unsignedBigInteger('attribute_value_id')->index();
            $table->boolean('bool_value')->nullable();

            $table->unique(['good_id', 'attribute_value_id']);

            $table->foreign('good_id')->references('id')->on(OnCatalogGood::getTableName())->cascadeOnDelete();
            $table->foreign('attribute_value_id')->references('id')->on(OnCatalogAttributeValue::getTableName())->cascadeOnDelete();
        });

        Schema::create(OnCatalogImage::getTableName(), function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('good_id')->index();
            $table->string('original_url')->nullable();
            $table->string('path')->nullable();
            $table->string('hash', 32)->index();
            $table->tinyInteger('type')->index();
            $table->tinyInteger('media_type')->index();

            $table->timestampTz('created_at')->useCurrent();

            $table->foreign('good_id')->references('id')->on(OnCatalogGood::getTableName())->onDelete('cascade');
        });

        Schema::create(OnCatalogMarket::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->integer('market_id')->index();
            $table->string('name');

            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();
        });

        Schema::create(OnCatalogPrice::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('good_id')->index();
            $table->unsignedBigInteger('market_id')->index();
            $table->decimal('price', 10, 2);

            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('good_id')->references('id')->on(OnCatalogGood::getTableName())->cascadeOnDelete();
            $table->foreign('market_id')->references('id')->on(OnCatalogMarket::getTableName())->cascadeOnDelete();
        });
    }

    public function down(): void {}
};
