<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('institute_tool', function (Blueprint $table): void {
            $table->mediumText('conditions_en')->change();
            $table->mediumText('conditions_nl')->change();
        });

        Schema::table('concept_institute_tools', function (Blueprint $table): void {
            $table->mediumText('conditions_en')->change();
            $table->mediumText('conditions_nl')->change();
        });
    }
};
