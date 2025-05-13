<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExtraService;
use Illuminate\Http\Request;

class ExtraServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $extraServices = ExtraService::all();
        return view('admin.extra_services.index', compact('extraServices'));
    }

    public function create()
    {
        return view('admin.extra_services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        ExtraService::create($validated);

        return redirect()->route('admin.extra-services.index')
            ->with('success', 'Extra service created successfully');
    }

    public function edit(ExtraService $extraService)
    {
        return view('admin.extra_services.edit', compact('extraService'));
    }

    public function update(Request $request, ExtraService $extraService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $extraService->update($validated);

        return redirect()->route('admin.extra-services.index')
            ->with('success', 'Extra service updated successfully');
    }

    public function destroy(ExtraService $extraService)
    {
        // Check if service is being used in any bookings
        if ($extraService->bookings()->count() > 0) {
            return redirect()->route('admin.extra-services.index')
                ->with('error', 'Cannot delete extra service that is used in bookings');
        }

        $extraService->delete();

        return redirect()->route('admin.extra-services.index')
            ->with('success', 'Extra service deleted successfully');
    }
}
