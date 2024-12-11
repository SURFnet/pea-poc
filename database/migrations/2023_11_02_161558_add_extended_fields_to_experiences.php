<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('experiences', function (Blueprint $table): void {
            $table->renameColumn('message', 'tool_usage');
            $table->mediumText('didactic_experience')->nullable()->after('message');
            $table->mediumText('recommend')->nullable()->after('didactic_experience');
        });
    }
};
