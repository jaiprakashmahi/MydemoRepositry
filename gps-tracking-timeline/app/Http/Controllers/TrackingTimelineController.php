<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\JourneyTimelineService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class TrackingTimelineController extends Controller
{
    public function __construct(protected JourneyTimelineService $timelineService)
    {
    }

    public function index(Request $request): View
    {
        $employees = Employee::where('is_active', true)->orderBy('name')->get();

        $selectedEmployee = $request->filled('employee_id')
            ? $employees->firstWhere('id', (int) $request->input('employee_id'))
            : $employees->first();

        $date = $request->input('date', Carbon::today()->toDateString());

        $timeline = $selectedEmployee
            ? $this->timelineService->build($selectedEmployee, $date)
            : null;

        return view('dashboard', [
            'employees' => $employees,
            'selectedEmployee' => $selectedEmployee,
            'date' => $date,
            'timeline' => $timeline,
        ]);
    }
}
