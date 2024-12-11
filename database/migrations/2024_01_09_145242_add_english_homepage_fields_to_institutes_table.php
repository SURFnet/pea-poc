<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('institutes', function (Blueprint $table): void {
            $table->renameColumn('homepage_title', 'homepage_title_nl');
            $table->renameColumn('homepage_body', 'homepage_body_nl');

            $table->string('homepage_title_en')->nullable()->after('homepage_title');
            $table->mediumText('homepage_body_en')->nullable()->after('homepage_body');
        });
    }
};
