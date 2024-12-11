<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('alternative_tool_institute_tools', function (Blueprint $table): void {
            $table->bigInteger('institute_tool_id')->unsigned();
            $table->foreignId('tool_id')->constrained()->cascadeOnDelete();

            $table
                ->foreign('institute_tool_id', 'alternative_institute_tool_foreign')
                ->references('id')
                ->on('institute_tool')
                ->onDelete('cascade');

            $table->unique(['institute_tool_id', 'tool_id'], 'institute_tool_and_tool_unique');
        });

        $this->linkOldAlternativeTools();

        Schema::table('institute_tool', function (Blueprint $table): void {
            $table->dropForeign('institute_tool_alternative_tool_id_foreign');
            $table->dropColumn('alternative_tool_id');
        });
    }

    public function linkOldAlternativeTools(): void
    {
        $oldAlternativeIds = DB::table('institute_tool')
            ->select('id', 'alternative_tool_id')
            ->whereNotNull('alternative_tool_id')
            ->get();

        foreach ($oldAlternativeIds as $oldAlternativeId) {
            DB::table('alternative_tool_institute_tools')->insert([
                'institute_tool_id' => $oldAlternativeId->id,
                'tool_id'           => $oldAlternativeId->alternative_tool_id,
            ]);
        }
    }
};
