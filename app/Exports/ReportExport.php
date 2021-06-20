<?php

namespace App\Exports;

use App\Exports\Sheets\OrderExport;
use App\Exports\Sheets\TotalReport;
use App\Exports\Sheets\ExpenseExport;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportExport implements WithMultipleSheets
{
  use Exportable;
  private $input = [];

  public function __construct(array $input)
  {
    $this->input = $input;
  }

  public function sheets(): array
  {
    $sheets = [];

    // $sheets[] = new TotalReport($this->input);
    $sheets[] = new OrderExport($this->input);
    $sheets[] = new ExpenseExport($this->input);

    return $sheets;
  }
}
