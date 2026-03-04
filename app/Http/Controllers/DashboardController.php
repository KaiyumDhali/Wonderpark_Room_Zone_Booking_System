<?php
namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $data['companySetting'] = CompanySetting::where('status', 1)->first();
        addVendors(['amcharts', 'amcharts-maps', 'amcharts-stock']);
        return view('pages.dashboards.index')->with($data);
    }
}
