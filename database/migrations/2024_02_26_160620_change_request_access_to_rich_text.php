<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        $this->performMigrationFor('institute_tool');
        $this->performMigrationFor('concept_institute_tools');
    }

    private function performMigrationFor(string $tableName): void
    {
        Schema::table($tableName, function (Blueprint $table): void {
            $table->renameColumn('request_access_url', 'request_access_en');
        });

        Schema::table($tableName, function (Blueprint $table): void {
            $table->mediumText('request_access_en')->change();
            $table->mediumText('request_access_nl')->nullable()->after('request_access_en');
        });

        $linkConversionSql = 'CONCAT("<a href=\"", request_access_en, "\">", request_access_en, "</a>")';
        DB::table($tableName)
            ->whereNotNull('request_access_en')
            ->update([
                'request_access_en' => DB::raw($linkConversionSql),
                'request_access_nl' => DB::raw('request_access_en'),
            ]);
    }
};
