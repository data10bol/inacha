<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use App\Configuration;
use App\Goal;
use App\Month;

class ExcelExport implements FromView
{

  public function view(): View
  {
    $goal = Goal::OrderBy('doing_id', 'ASC')->get();

    $months = Month::OrderBy('id', 'ASC')
      ->pluck('name', 'id');

    return view('layouts.report.poa.excel',
      compact(['goal', 'months'])
    );
  }

}
