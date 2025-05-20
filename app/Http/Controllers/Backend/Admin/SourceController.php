<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Source;
use Auth;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sources = Source::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $sources->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            });
        }

        $sources = $sources->paginate(10); // Adjust pagination as needed

        return view('admin.sources.index', compact('sources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sources.create');
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
            'name' => 'required|string|max:255',
            'commission_type' => 'required|in:on_cost,on_profit,fixed',
            'commission_value' => 'nullable|numeric',
            'is_platform' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string|max:10000',
            'created_by' => 'nullable|string|max:255',
        ]));

        $userId = Auth::user()->id;
        $validated['created_by'] = $userId;
        
        $source = Source::create($validated);

        return redirect()->route('sources.index', $source)
            ->with('success', 'Source created successfully.');
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
    public function edit(Source $source)
    {
        return view('admin.sources.edit', compact('source'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Source $source)
    {
        // Get dynamic keys for validation
        $validated = $request->validate(array_merge([
            'name' => 'required|string|max:255',
            'commission_type' => 'required|in:on_cost,on_profit,fixed',
            'commission_value' => 'nullable|numeric',
            'is_platform' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string|max:10000',
            'created_by' => 'nullable|string|max:255',
        ]));

        $userId = Auth::user()->id;
        $validated['updated_by'] = $userId;
        $validated['is_platform'] = $request->has('is_platform') ? true : false;
        $validated['is_active'] = $request->has('is_active') ? true : false;


        // dd($validated);
        $source->update($validated);

        return redirect()->route('sources.index', $source)
            ->with('success', 'Source updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Source $source)
    {
        try {
            // Begin transaction
            DB::beginTransaction();

            $source->delete();

            // Commit transaction
            DB::commit();

            return redirect()->route('sources.index', $source)
                ->with('success', 'Source deleted successfully.');
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete source. Please try again.');
        }
    }
}
