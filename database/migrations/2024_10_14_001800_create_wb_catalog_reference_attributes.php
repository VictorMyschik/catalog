<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wb_catalog_reference_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wb_catalog_reference_attributes');
    }
};
