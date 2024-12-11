<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table): void {
            $table->renameColumn('name', 'name_en');
            $table->renameColumn('description', 'description_en');
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->mediumText('name_nl')->after('name_en')->nullable();
            $table->mediumText('description_nl')->after('description_en')->nullable();
        });
    }
};
