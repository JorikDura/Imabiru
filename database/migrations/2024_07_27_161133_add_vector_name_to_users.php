<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        DB::statement(
            "
        ALTER TABLE users
            ADD search_name tsvector
                generated always as (to_tsvector('simple', name))
                    stored;
        "
        );
        DB::statement("CREATE INDEX search_name_gin ON users USING GIN(search_name)");
    }

    public function down(): void
    {
        DB::statement("DROP INDEX IF EXISTS search_name_gin");
        DB::statement("ALTER TABLE users DROP COLUMN search_name");
    }
};
