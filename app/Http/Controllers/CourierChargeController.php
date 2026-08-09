<?php

namespace App\Http\Controllers;

use App\Models\CourierCharge;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use Inertia\Inertia;

class CourierChargeController extends Controller
{
    /**
     * Admin page: list all district courier charges.
     */
    public function index()
    {
        $this->ensureTableExists();

        $charges = CourierCharge::orderByRaw("FIELD(zone_type, 'inside_dhaka', 'outside_dhaka', 'custom')")
            ->orderBy('district_name')
            ->get();

        $insideDhakaCount  = $charges->where('zone_type', 'inside_dhaka')->count();
        $outsideDhakaCount = $charges->where('zone_type', 'outside_dhaka')->count();
        $customCount       = $charges->where('zone_type', 'custom')->count();
        $activeCount       = $charges->where('is_active', true)->count();

        return Inertia::render('Admin/CourierCharges', [
            'charges' => $charges,
            'insideDhakaCount' => $insideDhakaCount,
            'outsideDhakaCount' => $outsideDhakaCount,
            'customCount' => $customCount,
            'activeCount' => $activeCount,
        ]);
    }

    /**
     * Add a new district courier charge.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'district_name' => 'required|string|max:100|unique:courier_charges,district_name',
            'charge'        => 'required|numeric|min:0',
            'zone_type'     => 'required|in:inside_dhaka,outside_dhaka,custom',
            'is_active'     => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        CourierCharge::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'District charge added successfully.']);
        }

        return redirect()->back()->with('success', "Courier charge for '{$validated['district_name']}' added successfully.");
    }

    /**
     * Update a single district courier charge.
     */
    public function update(Request $request, $id)
    {
        $charge = CourierCharge::findOrFail($id);

        $validated = $request->validate([
            'district_name' => 'required|string|max:100|unique:courier_charges,district_name,' . $id,
            'charge'        => 'required|numeric|min:0',
            'zone_type'     => 'required|in:inside_dhaka,outside_dhaka,custom',
            'is_active'     => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $charge->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Charge updated.', 'charge' => $charge->fresh()]);
        }

        return redirect()->back()->with('success', "Charge for '{$charge->district_name}' updated successfully.");
    }

    /**
     * Delete a district courier charge.
     */
    public function destroy(Request $request, $id)
    {
        $charge = CourierCharge::findOrFail($id);
        $name   = $charge->district_name;
        $charge->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => "'{$name}' removed."]);
        }

        return redirect()->back()->with('success', "Courier charge for '{$name}' deleted.");
    }

    /**
     * Bulk update all district charges at once (JSON payload from admin UI).
     */
    public function bulkUpdate(Request $request)
    {
        $rows = $request->input('charges', []);

        if (!is_array($rows) || empty($rows)) {
            return response()->json(['success' => false, 'message' => 'No data provided.'], 422);
        }

        $saved = 0;
        foreach ($rows as $row) {
            if (empty($row['id'])) continue;

            $charge = CourierCharge::find($row['id']);
            if (!$charge) continue;

            $charge->update([
                'charge'    => (float) ($row['charge'] ?? $charge->charge),
                'zone_type' => in_array($row['zone_type'] ?? '', ['inside_dhaka', 'outside_dhaka', 'custom'])
                                ? $row['zone_type'] : $charge->zone_type,
                'is_active' => isset($row['is_active']) ? (bool) $row['is_active'] : $charge->is_active,
            ]);
            $saved++;
        }

        return response()->json([
            'success' => true,
            'message' => "{$saved} district charges saved to database.",
        ]);
    }

    /**
     * PUBLIC API: Return delivery charge for a given city/district.
     */
    public function getCharge(Request $request)
    {
        $city = trim($request->input('city', ''));

        if (!$city) {
            return response()->json(['charge' => 120, 'zone_type' => 'outside_dhaka']);
        }

        // Exact match first
        $record = CourierCharge::where('is_active', true)
            ->whereRaw('LOWER(district_name) = ?', [strtolower($city)])
            ->first();

        // Partial match fallback
        if (!$record) {
            $record = CourierCharge::where('is_active', true)
                ->whereRaw('LOWER(district_name) LIKE ?', ['%' . strtolower($city) . '%'])
                ->first();
        }

        if ($record) {
            return response()->json([
                'charge'        => (float) $record->charge,
                'zone_type'     => $record->zone_type,
                'district_name' => $record->district_name,
            ]);
        }

        // Default: outside Dhaka rate
        return response()->json(['charge' => 120, 'zone_type' => 'outside_dhaka']);
    }

    /**
     * Return all active districts as JSON (for checkout dropdown).
     */
    public function allDistricts()
    {
        $districts = CourierCharge::where('is_active', true)
            ->orderByRaw("FIELD(zone_type, 'inside_dhaka', 'outside_dhaka', 'custom')")
            ->orderBy('district_name')
            ->get(['id', 'district_name', 'charge', 'zone_type']);

        return response()->json($districts);
    }

    /**
     * Self-healing: ensure the courier_charges table exists before queries.
     */
    protected function ensureTableExists(): void
    {
        if (!Schema::hasTable('courier_charges')) {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        }
    }
}
