<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_id')->index();
            $table->string('prefix', 500)->nullable();
            $table->string('name', 500);
            $table->string('short_info', 2000)->nullable(); //краткие сведения о товаре
            $table->string('description', 500)->nullable();    // для себя
            $table->unsignedBigInteger("manufacturer_id")->nullable()->index();
            $table->string("parent_good_id")->nullable()->index();
            $table->boolean("is_certification")->default(false);
            $table->integer('int_id')->nullable()->index();
            $table->string('string_id')->index();
            $table->string('link');
            $table->jsonb('sl')->nullable();

            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('type_id')->references('id')->on('catalog_types')->cascadeOnDelete();
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods');
    }
};
