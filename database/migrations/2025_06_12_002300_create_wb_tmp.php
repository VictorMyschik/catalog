<?php

use App\Models\Catalog\Wildberries\WBCatalogGood;
use App\Models\Catalog\Wildberries\WBCatalogImage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create(WBCatalogImage::getTableName(), function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('good_id')->index();
            $table->string('original_url')->nullable();
            $table->string('path')->nullable();
            $table->string('hash', 32)->index();
            $table->tinyInteger('type')->index();
            $table->tinyInteger('media_type')->index();

            $table->timestampTz('created_at')->useCurrent();

            $table->foreign('good_id')->references('id')->on(WBCatalogGood::getTableName())->onDelete('cascade');
        });
    }

    public function down(): void {}
};
