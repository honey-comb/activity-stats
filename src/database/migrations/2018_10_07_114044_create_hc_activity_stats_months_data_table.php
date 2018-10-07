<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHcActivityStatsMonthsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hc_activity_stats_months_data', function(Blueprint $table) {
            $table->increments('count');
            $table->uuid('id')->unique();
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('deleted_at')->nullable();

            $table->uuid('type_id');

            $table->string('amountable_id');
            $table->uuid('amountable_type');

            $table->date('date');
            $table->integer('amount');

            $table->foreign('type_id')->references('id')->on('hc_activity_stats_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hc_activity_stats_months_data', function(Blueprint $table) {
            $table->dropForeign(['type_id']);
        });

        Schema::dropIfExists('hc_activity_stats_months_data');
    }
}
