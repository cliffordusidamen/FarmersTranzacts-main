<?php

namespace App\Exports\Sheets;

use App\Models\Farm;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class FarmYearSheet implements FromCollection, WithTitle
{

    private Farm $farm;
    private int $year;

    public function __construct(Farm $farm, int $year)
    {
        $this->farm = $farm;
        $this->year = $year;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $records = [];

        $seasons = $this->farm->seasons()
            ->with(['seasonInputs', 'seasonProduces'])
            ->where('year', $this->year)
            ->get();

        foreach ($seasons as $season) {

            $records[] = ["{$season->year} - {$season->season} season"];
            $records[] = ["Inputs/Materials/Expenses"];
            $records[] = ["Item", "Unit", "Quantity", "Unit Price"];

            $season->seasonInputs->each(function ($seasonInput) use (&$records) {
                $records[] = [
                    $seasonInput->name,
                    $seasonInput->unit,
                    $seasonInput->quantity,
                    $seasonInput->cost_per_unit,
                ];
            });

            $records[] = [""];
            $records[] = [""];
            $records[] = ["Outputs/Produce"];
            $records[] = ["Item", "Unit", "Quantity produced", "Quantity sold", "Unit Price"];

            $season->seasonProduces->each(function ($seasonInput) use (&$records) {
                $records[] = [
                    $seasonInput->name,
                    $seasonInput->unit,
                    $seasonInput->quantity_produced,
                    $seasonInput->quantity_sold,
                    $seasonInput->price_per_unit,
                ];
            });
            $records[] = [""];
            $records[] = [""];
        }

        return collect($records);
    }

    /**
     * Prepare query for fetching data from the database
     *
     */
    public function query()
    {
        return $this->farm->seasons()->where('year', $this->year);
    }

    /**
     * Set the title of the sheet
     *
     * @return string
     */
    public function title(): string
    {
        return $this->year;
    }
}
