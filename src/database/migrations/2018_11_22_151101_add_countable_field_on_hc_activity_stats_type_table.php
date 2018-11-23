<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddCountableFieldOnHcActivityStatsTypeTable
 */
class AddCountableFieldOnHcActivityStatsTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('hc_activity_stats_type', function (Blueprint $table) {
            $table->boolean('countable')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {

        Schema::table('hc_activity_stats_type', function (Blueprint $table) {
            $table->dropColumn('countable');
        });
    }
}
