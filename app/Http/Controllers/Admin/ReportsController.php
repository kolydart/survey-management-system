<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Questionnaire;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function questionnaires(Request $request)
    {
        if ($request->has('date_filter')) {
            $parts = explode(' - ', $request->input('date_filter'));
            $date_from = Carbon::createFromFormat(config('app.date_format'), $parts[0])->format('Y-m-d');
            $date_to = Carbon::createFromFormat(config('app.date_format'), $parts[1])->format('Y-m-d');
        } else {
            $date_from = new Carbon('last Monday');
            $date_to = new Carbon('this Sunday');
        }
        $reportTitle = 'Questionnaires';
        $reportLabel = 'COUNT';
        $chartType = 'bar';

        $results = Questionnaire::where('created_at', '>=', $date_from)->where('created_at', '<=', $date_to)->get()->sortBy('created_at')->groupBy(function ($entry) {
            if ($entry->created_at instanceof \Carbon\Carbon) {
                return \Carbon\Carbon::parse($entry->created_at)->format('Y-m-d');
            }

            return \Carbon\Carbon::createFromFormat(config('app.date_format'), $entry->created_at)->format('Y-m-d');
        })->map(function ($entries, $group) {
            return $entries->count('id');
        });

        return view('admin.reports', compact('reportTitle', 'results', 'chartType', 'reportLabel'));
    }
}
