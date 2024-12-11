<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->unsignedBigInteger('impersonated_institute_id')->nullable()->after('institute_id');

            $table->foreign('impersonated_institute_id')
                ->references('id')->on('institutes')
                ->onUpdate('restrict')
                ->nullOnDelete();
        });
    }
};
