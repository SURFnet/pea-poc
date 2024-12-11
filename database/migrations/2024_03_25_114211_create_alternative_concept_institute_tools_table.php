<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('alternative_concept_institute_tools', function (Blueprint $table): void {
            $table->bigInteger('concept_institute_tool_id')->unsigned();
            $table->foreignId('tool_id')->constrained()->cascadeOnDelete();

            $table
                ->foreign('concept_institute_tool_id', 'alternative_concept_institute_tool_foreign')
                ->references('id')
                ->on('concept_institute_tools')
                ->onDelete('cascade');

            $table->unique(['concept_institute_tool_id', 'tool_id'], 'concept_institute_tool_and_tool_id_unique');
        });

        $this->linkOldAlternativeTools();

        Schema::table('concept_institute_tools', function (Blueprint $table): void {
            $table->dropForeign('concept_institute_tools_alternative_tool_id_foreign');
            $table->dropColumn('alternative_tool_id');
        });
    }

    public function linkOldAlternativeTools(): void
    {
        $oldAlternativeIds = DB::table('concept_institute_tools')
            ->select('id', 'alternative_tool_id')
            ->whereNotNull('alternative_tool_id')
            ->get();

        foreach ($oldAlternativeIds as $oldAlternativeId) {
            DB::table('alternative_concept_institute_tools')->insert([
                'concept_institute_tool_id' => $oldAlternativeId->id,
                'tool_id'                   => $oldAlternativeId->alternative_tool_id,
            ]);
        }
    }
};
