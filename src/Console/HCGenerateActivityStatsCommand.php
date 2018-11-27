<?php
/**
 * @copyright 2018 interactivesolutions
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
 * Contact InteractiveSolutions:
 * E-mail: info@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

namespace HoneyComb\ActivityStats\Console;

use HoneyComb\ActivityStats\DTO\HCActivityStatsDTO;
use HoneyComb\ActivityStats\Repositories\Admin\HCActivityStatsCronRepository;
use HoneyComb\ActivityStats\Repositories\Admin\HCActivityStatsDetailedDataRepository;
use HoneyComb\ActivityStats\Repositories\Admin\HCActivityStatsRepository;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class HCGenerateActivityStatsDays
 * @package HoneyComb\ActivityStats\Console
 */
class HCGenerateActivityStatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hc:activity-data';

    /**
     * @var HCActivityStatsDetailedDataRepository
     */
    private $detailedRepository;

    /**
     * @var HCActivityStatsRepository
     */
    private $datesRepository;
    /**
     * @var HCActivityStatsCronRepository
     */
    private $cronRepository;

    /**
     * HCGenerateActivityStatsCommand constructor.
     * @param HCActivityStatsDetailedDataRepository $detailedRepository
     * @param HCActivityStatsRepository $datesRepository
     * @param HCActivityStatsCronRepository $cronRepository
     */
    public function __construct(
        HCActivityStatsDetailedDataRepository $detailedRepository,
        HCActivityStatsRepository $datesRepository,
        HCActivityStatsCronRepository $cronRepository
    ) {
        parent::__construct();
        $this->detailedRepository = $detailedRepository;
        $this->datesRepository = $datesRepository;
        $this->cronRepository = $cronRepository;
    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate activity stats';

    public function handle(): void
    {
        $start = Carbon::now();

        $this->info('Start ' . Carbon::now());

        $lastImportedDate = $this->cronRepository->makeQuery()->select('date')->first();
        if (isset($lastImportedDate)) {
            $lastImportedDate = new Carbon($lastImportedDate->date);
        } else {
            $lastImportedDate = Carbon::minValue();
        }
        //TODO move to repo
        $items = $this->detailedRepository->makeQuery()
            ->select('type_id', 'amountable_id', 'amountable_type', 'date', 'amount')
            ->whereHas('type', function ($query) {
                $query->where('countable', true);
            })
            ->setEagerLoads([])
            ->with([
                'type' => function ($query) {
                    $query->setEagerLoads([])
                        ->select('id');
                },
            ])
            ->whereDate('date', '<=', $start)
            ->whereDate('date', '>', $lastImportedDate)->get();

        $this->detailedToDays($items);
        $this->detailedToMonths($items);
        $this->detailedToYears($items);

        //TODO add detailedTotal
        //$this->detailedTotal($items);

        $this->updateCronTimestamp($start);
        $this->info('End ' . Carbon::now());
    }

    /**
     * @param Collection $items
     */
    private function detailedToDays(Collection $items): void
    {
        $items = $items->groupBy([
            function ($item) {
                return $item->type->id;
            },
            'amountable_type',
            'amountable_id',
            function ($item) {
                return Carbon::parse($item->date)->toDateString();
            },
            'amount',
        ], true)
            ->toArray();
        $this->formatEntries(1, $items);
    }

    /**
     * @param Collection $items
     */
    private function detailedToMonths(Collection $items): void
    {
        $items = $items->groupBy([
            function ($item) {
                return $item->type->id;
            },
            'amountable_type',
            'amountable_id',
            function ($item) {
                return Carbon::parse($item->date)->startOfMonth()->toDateString();
            },
            'amount',
        ], true)->toArray();
        $this->formatEntries(2, $items);
    }

    /**
     * @param Collection $items
     */
    private function detailedToYears(Collection $items)
    {
        $items = $items->groupBy([
            function ($item) {
                return $item->type->id;
            },
            'amountable_type',
            'amountable_id',
            function ($item) {
                return Carbon::parse($item->date)->startOfYear()->toDateString();
            },
            'amount',
        ], true)->toArray();
        $this->formatEntries(3, $items);
    }

    /**
     * @param Carbon $start
     */
    private function updateCronTimestamp(Carbon $start): void
    {
        $this->cronRepository->makeQuery()->updateOrCreate(['date' => $start]);
    }

    /**
     * @param int $dateType
     * @param array $items
     */
    private function formatEntries(int $dateType, array $items): void
    {
        $items = array_dot($items);
        $items = array_where($items, function ($value, $key) {
            $exploded = explode('.', $key);
            if (last($exploded) == 'amount') {
                return ($value);
            }
        });
        $items = array_keys($items);

        $new = [];
        array_walk($items, function ($item, $key) use (&$new) {
            $withoutAmountKey = substr($item, 0, strripos($item, '.'));
            $withoutKey = substr($withoutAmountKey, 0, strripos($withoutAmountKey, '.'));

            $key = substr($withoutKey, 0, strripos($withoutKey, '.'));
            $value = substr($withoutKey, strripos($withoutKey, '.') + 1);

            $new[$key][] = $value;
        });

        $entries = [];
        foreach ($new as $key => $item) {
            $values = explode('.', $key);
            $entry['type'] = head($values);
            $entry['amount'] = array_sum($item);

            if (array_key_exists('1', $values)) {
                $entry['amountable_type'] = $values[1];
            }
            if (array_key_exists('2', $values)) {
                $entry['amountable_id'] = $values[2];
            }
            if (array_key_exists('3', $values)) {
                $entry['date'] = $values[3];
            }
            $entries[] = $entry;
        }
        $this->insertEntries($dateType, $entries);
    }

    /**
     * @param int $dateType
     * @param array $entries
     */
    private function insertEntries(int $dateType, array $entries): void
    {
        foreach ($entries as $entry) {

            $this->datesRepository->updateAmount(
                new $entry['amountable_type'],
                new HCActivityStatsDTO(
                    $dateType,
                    new Carbon($entry['date']),
                    $entry['amount'],
                    $entry['type'],
                    $entry['amountable_id']
                )
            );
        }
    }
}
