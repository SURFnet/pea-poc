<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('tools', function (Blueprint $table): void {
            $table->renameColumn('personal_data', 'personal_data_en');
            $table->string('personal_data_nl')->nullable();
        });

        Schema::table('concept_tools', function (Blueprint $table): void {
            $table->renameColumn('personal_data', 'personal_data_en');
            $table->string('personal_data_nl')->nullable();
        });
    }
};
