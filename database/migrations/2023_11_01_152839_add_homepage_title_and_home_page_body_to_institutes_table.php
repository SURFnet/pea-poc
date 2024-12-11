<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('institutes', function (Blueprint $table): void {
            $table->string('homepage_title')->nullable()->after('domain');
            $table->text('homepage_body')->nullable()->after('homepage_title');
        });
    }
};
