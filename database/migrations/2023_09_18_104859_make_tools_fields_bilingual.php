<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('tools', function (Blueprint $table): void {
            $table->dropUnique(['name']);
        });

        Schema::table('tools', function (Blueprint $table): void {
            $table->renameColumn('name', 'name_en');
            $table->unique('name_en');

            $table->renameColumn('description_short', 'description_short_en');
            $table->renameColumn('description_long_1', 'description_long_1_en');
            $table->renameColumn('description_long_2', 'description_long_2_en');
        });

        Schema::table('tools', function (Blueprint $table): void {
            $table->string('name_nl')->after('name_en')->nullable()->unique();

            $table->text('description_short_nl')->after('description_short_en')->nullable();
            $table->mediumText('description_long_1_nl')->after('description_long_1_en')->nullable()->default(null);
            $table->mediumText('description_long_2_nl')->after('description_long_2_en')->nullable()->default(null);
        });
    }
};
