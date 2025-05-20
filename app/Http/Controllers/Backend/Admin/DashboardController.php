<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
// use App\Models\TeamMember; // Assuming you have a TeamMember model
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $totalClients = Client::count();
        $totalProjects = Project::count();
        $totalHoldProjects = Project::where('status', 'hold')->count();
        $totalCancelledProjects = Project::where('status', 'cancelled')->count();
        $totalActiveProjects = Project::where('status', 'ongoing')->count();
        $totalCompletedProjects = Project::where('status', 'completed')->count();
        $totalTeamMembers = 0; // Assuming you have a TeamMember model
        // $totalTeamMembers = TeamMember::count(); // Assuming you have a TeamMember model

        $monthlyLabels = [];
        $monthlyCounts = [];

        for ($i = 0; $i < 6; $i++) {
            $month = Carbon::now()->subMonths($i);
            $monthlyLabels[] = $month->format('F');
            $monthlyCounts[] = \App\Models\Project::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
        }

        $monthlyLabels = array_reverse($monthlyLabels);
        $monthlyCounts = array_reverse($monthlyCounts);

        return view('admin.dashboard', compact(
            'totalClients', 
            'totalProjects', 
            'totalHoldProjects',
            'totalCancelledProjects',
            'totalActiveProjects', 
            'totalCompletedProjects',
            'monthlyLabels',
            'monthlyCounts'
        ));
    }
}
