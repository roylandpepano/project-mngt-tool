<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display a paginated list of activity logs. Admin only.
     */
    public function index(Request $request)
    {
        $query = Activity::query()->latest();

        // optional filter by log name or event
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->input('log_name'));
        }

        if ($request->filled('event')) {
            $query->where('event', $request->input('event'));
        }

        $activities = $query->paginate(10)->withQueryString();

        return view('activity_logs.index', compact('activities'));
    }
}
