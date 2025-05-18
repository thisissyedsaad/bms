<?php

namespace App\Http\Controllers\Backend\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Client;
use Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $clients = Client::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $clients->where(function ($query) use ($searchTerm) {
                $query->where('full_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('mobile_number', 'like', '%' . $searchTerm . '%')
                    ->orWhere('profile_name', 'like', '%' . $searchTerm . '%');
                // Add more fields to search as needed
            });
        }

        if ($request->has('platform') && $request->input('platform') != '') {
            $clients->where('platform', $request->input('platform'));
        }

        $clients = $clients->paginate(10); // Adjust pagination as needed

        return view('admin.clients.index', compact('clients'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::get();
        return view('admin.clients.create', compact('countries'));
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
            'full_name' => 'required|string|max:255',
            'profile_name' => 'required|string|max:255|unique:clients',
            'email' => 'required|email|unique:clients',
            'profile_photo' => 'nullable|image|max:2048',
            'tagline' => 'nullable|string|max:50',
            'mobile_number' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'profile_description' => 'nullable|string|max:10000',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country_id' => 'nullable|string|max:100',
            'show_city' => 'nullable|boolean',
            'show_country' => 'nullable|boolean',
            'website' => 'nullable|url|max:255',
            'platform' => 'nullable|string|max:255',
            'reference_by' => 'nullable|string|max:255',
        ]));

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('clients', 'public');
        }

        $userId = Auth::user()->id;
        $validated['created_by'] = $userId;
        
        // Extract address data
        $addressData = [
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'country_id' => $request->country_id,
            'show_city' => $request->has('show_city') ? true : false,
            'show_country' => $request->has('show_country') ? true : false,
        ];

        $client = Client::create($validated);

        $client->address()->create($addressData);

        return redirect()->route('clients.index', $client)
            ->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::with('projects')->findOrFail($id);
        return view('admin.clients.view', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $client->load(['address']);
        // Get data, not just those with logos
        $countries = Country::get();

        return view('admin.clients.edit', compact('client', 'countries'));
    }

    public function update(Request $request, Client $client)
    {
        // Get dynamic keys for validation
        $validated = $request->validate(array_merge([
            'full_name' => 'required|string|max:255',
            'profile_name' => 'required|string|max:255|unique:clients,profile_name,' . $client->id,
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'profile_photo' => 'nullable|image|max:2048',
            'tagline' => 'nullable|string|max:50',
            'mobile_number' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'profile_description' => 'nullable|string|max:10000',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country_id' => 'nullable|string|max:100',
            'show_city' => 'nullable|boolean',
            'show_country' => 'nullable|boolean',
            'website' => 'nullable|url|max:255',
            'platform' => 'nullable|string|max:255',
            'reference_by' => 'nullable|string|max:255',
        ]));

        if ($request->hasFile('profile_photo')) {
            if ($client->profile_photo) {
                Storage::disk('public')->delete($client->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('clients', 'public');
        }

        $userId = Auth::user()->id;
        $validated['updated_by'] = $userId;

        $client->update($validated);

        // Update or create address
        $addressData = [
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'country_id' => $request->country_id,
            'show_city' => $request->has('show_city') ? true : false,
            'show_country' => $request->has('show_country') ? true : false,
        ];

        if ($client->address) {
            $client->address->update($addressData);
        } else {
            $client->address()->create($addressData);
        }

        return redirect()->route('clients.index', $client)
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        try {
            // Begin transaction
            DB::beginTransaction();

            // Delete profile photo if exists
            if ($client->profile_photo) {
                Storage::disk('public')->delete($client->profile_photo);
            }
            $client->delete();

            // Commit transaction
            DB::commit();

            return redirect()->route('clients.index', $client)
                ->with('success', 'Client deleted successfully.');
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete client. Please try again.');
        }
    }
}
