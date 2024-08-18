<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Таблица магазинов, размещённых в каталоге
     * Например на onliner размещены магазины: 21vek, imarket...
     */
    public function up(): void
    {
        Schema::create('catalog_markets', function (Blueprint $table) {
            $table->id();
            $table->integer('market_id')->index();
            $table->string('name');

            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_markets');
    }
};
