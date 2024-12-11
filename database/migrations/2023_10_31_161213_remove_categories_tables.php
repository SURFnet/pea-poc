<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    public function up(): void
    {
        Schema::drop('concept_tool_categories');
        Schema::drop('tool_categories');
        Schema::drop('categories');
    }
};
