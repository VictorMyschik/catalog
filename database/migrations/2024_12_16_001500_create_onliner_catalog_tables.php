<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('address', 1000)->nullable();

            $table->timestampTz('created_at')->useCurrent();
        });
        Schema::create('catalog_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index();
            $table->jsonb('sl')->nullable();
            $table->string('json_link')->nullable();
        });

        Schema::create('catalog_group_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->index();
            $table->string('name', 100);
            $table->integer('sort')->default(0);

            $table->foreign('group_id')->references('id')->on('catalog_groups')->cascadeOnDelete();
        });

        Schema::create('catalog_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_attribute_id')->index();
            $table->string('name', 100);
            $table->string('description', 8000)->nullable();
            $table->integer('sort')->default(0);

            $table->foreign('group_attribute_id')->references('id')->on('catalog_group_attributes')->cascadeOnDelete();
        });

        Schema::create('catalog_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('catalog_attribute_id')->index();
            $table->string('text_value', 8000)->nullable()->index();

            $table->unique(['catalog_attribute_id', 'text_value']);

            $table->foreign('catalog_attribute_id')->references('id')->on('catalog_attributes')->cascadeOnDelete();
        });

        Schema::create('catalog_goods', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(0)->index();
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

            $table->foreign('group_id')->references('id')->on('catalog_groups')->cascadeOnDelete();
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('set null');
        });

        Schema::create('good_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('good_id')->index();
            $table->unsignedBigInteger('attribute_value_id')->index();
            $table->boolean('bool_value')->nullable();

            $table->unique(['good_id', 'attribute_value_id']);

            $table->foreign('good_id')->references('id')->on('catalog_goods')->cascadeOnDelete();
            $table->foreign('attribute_value_id')->references('id')->on('catalog_attribute_values')->cascadeOnDelete();
        });

        Schema::create('catalog_images', function (Blueprint $table): void {
            $table->id();
            $table->string('file_name', 50);
            $table->unsignedBigInteger('good_id')->index();
            $table->string('original_url')->nullable();
            $table->string('path');
            $table->string('hash', 32)->index();
            $table->tinyInteger('type')->index();
            $table->tinyInteger('media_type')->index();

            $table->timestampTz('created_at')->useCurrent();

            $table->foreign('good_id')->references('id')->on('catalog_goods')->onDelete('cascade');
        });

        Schema::create('catalog_markets', function (Blueprint $table) {
            $table->id();
            $table->integer('market_id')->index();
            $table->string('name');

            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();
        });

        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('good_id')->index();
            $table->unsignedBigInteger('market_id')->index();
            $table->decimal('price', 10, 2);

            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('good_id')->references('id')->on('catalog_goods')->cascadeOnDelete();
            $table->foreign('market_id')->references('id')->on('catalog_markets')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_markets');
        Schema::dropIfExists('manufacturers');
        Schema::dropIfExists('catalog_images');
        Schema::dropIfExists('good_attributes');
        Schema::dropIfExists('prices');
        Schema::dropIfExists('catalog_goods');
        Schema::dropIfExists('catalog_attribute_values');
        Schema::dropIfExists('catalog_attributes');
        Schema::dropIfExists('catalog_group_attributes');
        Schema::dropIfExists('catalog_groups');
    }
};
