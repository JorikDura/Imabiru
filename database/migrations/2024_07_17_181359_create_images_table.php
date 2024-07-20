<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('images', static function (Blueprint $table) {
            $table->id();
            $table->morphs('imageable');
            $table->string('image_name');
            $table->string('image_name_scaled')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
