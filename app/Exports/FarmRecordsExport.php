<?php

namespace App\Exports;

use App\Exports\Sheets\FarmYearSheet;
use App\Models\Farm;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FarmRecordsExport implements WithMultipleSheets
{
    use Exportable;

    protected $farm;

    public function __construct(Farm $farm)
    {
        $this->farm = $farm;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        dd('Here');
        $seasonInputs = $this->farm->seasons()->get()->map(function ($season) {
            return $season->inputs()->get();
        });
        return Farm::all();
    }

    /**
     * Prepare sheet for each year
     * 
     * @return array
     */
    public function sheets(): array
    {
        $yearSheets = [];

        $years = $this->farm->seasons()->get()->map(function ($season) {
            return $season->year;
        })->unique();

        foreach ($years as $year) {
            $yearSheets[] = new FarmYearSheet($this->farm, $year);
        }
        return $yearSheets;
    }
}
