<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('concept_institute_tools', function (Blueprint $table): void {
            $table->string('sla_url')->change();
        });

        Schema::table('institute_tool', function (Blueprint $table): void {
            $table->string('sla_url')->change();
        });
    }
};
