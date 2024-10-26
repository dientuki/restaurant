<?php

namespace App\Http\Controllers;

use App\Models\Table;

class DashboardController extends Controller
{
    public function index()
    {
        $tables = Table::getStatusForDateTime();

        //dd($tables);

        return view('dashboard', compact('tables'));
    }
}
