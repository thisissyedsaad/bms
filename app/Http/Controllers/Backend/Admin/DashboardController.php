<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
// use App\Models\TeamMember; // Assuming you have a TeamMember model

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
        $totalActiveProjects = Project::where('status', 'ongoing')->count();
        $totalCompletedProjects = Project::where('status', 'completed')->count();
        $totalTeamMembers = 0; // Assuming you have a TeamMember model
        // $totalTeamMembers = TeamMember::count(); // Assuming you have a TeamMember model

        return view('admin.dashboard', compact(
            'totalClients',
            'totalProjects',
            'totalActiveProjects',
            'totalTeamMembers',
            'totalCompletedProjects'
        ));
    }
}
