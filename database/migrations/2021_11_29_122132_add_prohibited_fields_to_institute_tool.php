<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProhibitedFieldsToInstituteTool extends Migration
{
    public function up(): void
    {
        Schema::table('institute_tool', function (Blueprint $table): void {
            $table->text('why_unfit')->after('status')->nullable();

            $table->unsignedBigInteger('alternative_tool_id')->after('tool_id')->nullable();

            $table->foreign('alternative_tool_id')
                ->references('id')->on('tools')
                ->onUpdate('RESTRICT')
                ->onDelete('SET NULL');
        });
    }

    public function down(): void
    {
        Schema::table('institute_tool', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('alternative_tool_id');

            $table->dropColumn('why_unfit');
        });
    }
}
