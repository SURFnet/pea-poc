<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddWay2translateLanguageTable extends Migration
{
    public function up(): void
    {
        Schema::create('way2translate_languages', function (Blueprint $table): void {
            $table->string('locale', 10)->primary();
            $table->dateTime('activated_at')->nullable();
            $table->timestamps();
        });

        $locales = DB::table('way2translate_activated_locales')
            ->select('locale', 'created_at')->get();

        foreach ($locales as $locale) {
            DB::table('way2translate_languages')->insert([
                'locale'       => $locale->locale,
                'activated_at' => $locale->created_at,
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ]);
        }

        Schema::drop('way2translate_activated_locales');
    }

    public function down(): void
    {
        Schema::create('way2translate_activated_locales', function (Blueprint $table): void {
            $table->string('locale')->unique()->index();
            $table->timestamps();
        });

        $locales = DB::table('way2translate_languages')
            ->select('locale', 'activated_at')->get();

        foreach ($locales as $locale) {
            DB::table('way2translate_activated_locales')->insert([
                'locale'     => $locale->locale,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        Schema::drop('way2translate_languages');
    }
}
