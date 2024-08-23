<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->boolean('active')->default(false);
            $table->string('category', 50)->nullable()->comment('Grouping');
            $table->string('name');
            $table->string('code_key', 50)->comment('Key for use in code');
            $table->string('value', 1000);
            $table->string('description')->nullable();

            $table->unique(['code_key']);

            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
