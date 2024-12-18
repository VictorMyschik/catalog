<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wb_catalog_countries', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('wb_id')->index();
            $table->string('name')->index();
            $table->string('full_name');

            $table->timestampTz('created_at')->useCurrent();

            $table->unique('wb_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wb_catalog_countries');
    }
};
