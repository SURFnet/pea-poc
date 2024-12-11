<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('custom_fields', function (Blueprint $table): void {
            $table->string('tab_type')->default('product')->after('institute_id');
        });
    }
};
