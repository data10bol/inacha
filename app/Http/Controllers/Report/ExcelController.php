<?php

namespace App\Http\Controllers\Report;

use App\Exports\ExcelExport;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class ExcelController extends Controller
{
  public function excel()
  {
    return Excel::download(new ExcelExport, 'poa.xlsx');
  }

  public function pdf()
  {
    return Excel::download(new ExcelExport, 'poa.pdf');
  }

}
