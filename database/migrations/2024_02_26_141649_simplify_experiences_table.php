<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('experiences', function (Blueprint $table): void {
            $table->mediumText('message')->after('title')->nullable();
            $table->dropColumn([
                'tool_usage',
                'didactic_experience',
                'recommend',
                'pros',
                'cons',
            ]);
        });
    }
};
