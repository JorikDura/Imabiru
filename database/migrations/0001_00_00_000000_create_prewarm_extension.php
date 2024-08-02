<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        DB::statement("CREATE EXTENSION IF NOT EXISTS pg_prewarm;");
        DB::raw("ALTER SYSTEM SET shared_preload_libraries = 'pg_prewarm';");
    }
};
