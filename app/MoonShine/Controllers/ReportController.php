<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers;

use App\Imports\ReportImport;
use MoonShine\Http\Controllers\MoonShineController;
use Maatwebsite\Excel\Facades\Excel;

final class ReportController extends MoonShineController
{
    public function total()
    {
        return Excel::download(new ReportImport, 'report.xlsx');
    }
}
