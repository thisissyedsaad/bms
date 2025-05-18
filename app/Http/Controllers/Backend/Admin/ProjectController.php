<?php

namespace App\Http\Controllers\Backend\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use App\Models\Country;
use Auth;
use Carbon\Carbon;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $projects = Project::query();

        // Search Filter
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $projects->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('client', function ($q) use ($searchTerm) {
                        $q->where('full_name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhere('project_type', 'like', '%' . $searchTerm . '%')
                    ->orWhere('assigned_to', 'like', '%' . $searchTerm . '%');
            });
        }

        // Client Filter
        if ($request->has('client_id') && $request->input('client_id') != '') {
            $projects->where('client_id', $request->input('client_id'));
        }

        // Project Type Filter
        if ($request->has('project_type') && $request->input('project_type') != '') {
            $projects->where('project_type', strtolower($request->input('project_type')));
        }

        // Status Filter
        if ($request->has('status') && $request->input('status') != '') {
            $projects->where('status', strtolower($request->input('status')));
        }

        // Date Range Filter
        if ($request->has('date_range') && $request->input('date_range') != '') {
            $dateRange = $request->input('date_range');
            
            if ($dateRange === 'last_week') {
                $projects->whereBetween('start_date', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
            } elseif ($dateRange === 'last_month') {
                $projects->whereBetween('start_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()]);
            } elseif ($dateRange === 'last_year') {
                $projects->whereBetween('start_date', [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()]);
            } elseif ($dateRange === 'custom') {
                // Fix for date filtering
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    // Both dates provided
                    $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                    $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                    $projects->whereBetween('start_date', [$startDate, $endDate]);
                } elseif ($request->filled('start_date')) {
                    // Only start date provided
                    $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                    $projects->whereDate('start_date', '>=', $startDate);
                } elseif ($request->filled('end_date')) {
                    // Only end date provided
                    $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                    $projects->whereDate('start_date', '<=', $endDate);
                }
            }
        }

        $projects = $projects->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        $allClients = Client::orderBy('full_name')->get();

        return view('admin.projects.index', compact('projects', 'allClients'));
    }
        

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::get();
        return view('admin.projects.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get dynamic keys for validation
        $validated = $request->validate(array_merge([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'conversion_rate' => 'nullable|numeric',
            'total_amount' => 'nullable|numeric',
            'received_amount' => 'nullable|numeric',
            'currency' => 'nullable|string|max:10',
            'project_type' => 'required|in:fixed,hourly',
            'details' => 'nullable|string|max:10000',
            'hourly_rate' => 'nullable|numeric',
            'estimated_hours' => 'nullable|numeric',
            'assigned_to' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'platform' => 'required|in:direct,upwork,reference',
            'status' => 'required|in:ongoing,completed,cancelled,hold',
        ]));

        $userId = Auth::user()->id;
        $validated['created_by'] = $userId;
        $Project = Project::create($validated);

        return redirect()->route('projects.index', $Project)
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $countries = Country::get();
        $clients = Client::get();
        return view('admin.projects.edit', compact('project', 'countries', 'clients'));
    }

    public function update(Request $request, Project $project)
    {
        // Get dynamic keys for validation
        $validated = $request->validate(array_merge([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'conversion_rate' => 'nullable|numeric',
            'total_amount' => 'nullable|numeric',
            'received_amount' => 'nullable|numeric',
            'currency' => 'nullable|string|max:10',
            'project_type' => 'required|in:fixed,hourly',
            'details' => 'nullable|string|max:10000',
            'hourly_rate' => 'nullable|numeric',
            'estimated_hours' => 'nullable|numeric',
            'assigned_to' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'platform' => 'required|in:direct,upwork,reference',
            'status' => 'required|in:ongoing,completed,cancelled,hold',
        ]));

        $userId = Auth::user()->id;
        $validated['updated_by'] = $userId;

        $project->update($validated);

        return redirect()->route('projects.index', $project)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $Project)
    {
        try {
            // Begin transaction
            DB::beginTransaction();

            // Delete profile photo if exists
            if ($Project->profile_photo) {
                Storage::disk('public')->delete($Project->profile_photo);
            }
            $Project->delete();

            // Commit transaction
            DB::commit();

            return redirect()->route('projects.index', $Project)
                ->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete Project. Please try again.');
        }
    }
}
