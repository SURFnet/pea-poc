<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('tools', function (Blueprint $table): void {
            $table->mediumText('personal_data_nl')->after('personal_data_en')->change();
        });

        Schema::table('concept_tools', function (Blueprint $table): void {
            $table->mediumText('personal_data_nl')->after('personal_data_en')->change();
        });
    }
};
