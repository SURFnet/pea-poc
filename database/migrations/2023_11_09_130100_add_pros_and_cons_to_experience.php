<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('experiences', function (Blueprint $table): void {
            $table->text('pros')->nullable()->after('recommend');
            $table->text('cons')->nullable()->after('pros');
        });
    }
};
