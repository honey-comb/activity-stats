<?php
/**
 * @copyright 2018 innovationbase
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InnovationBase:
 * E-mail: hello@innovationbase.eu
 * https://innovationbase.eu
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class RemoveTimestampsOnHcActivitiesTable
 */
class RemoveTimestampsOnHcActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $updateTables = [
            'hc_activity_stats_total_data',
            'hc_activity_stats_days_data',
            'hc_activity_stats_months_data',
            'hc_activity_stats_years_data',
        ];

        $removeColumns = [
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        foreach ($updateTables as $updateTable) {
            Schema::table($updateTable, function (Blueprint $table) use ($updateTable, $removeColumns) {
                foreach ($removeColumns as $removeColumn) {
                    if (Schema::hasColumn($updateTable, $removeColumn)) {
                        $table->dropColumn($removeColumn);
                    }
                }
            });
        }

        if (Schema::hasColumn(head($updateTables), 'date')) {
            Schema::table(head($updateTables), function (Blueprint $table) {
                $table->dropColumn('date');
            });
        }
    }
}
