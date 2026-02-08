<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanStoreRequest;
use App\Http\Requests\PlanUpdateRequest;
use App\Models\Plan;
use Illuminate\Support\Str;

class PlanAdminController extends Controller
{
    public function index()
    {
        $plans = Plan::query()->orderBy('price')->paginate(20);

        return view('admin.plans.index', [
            'plans' => $plans,
        ]);
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(PlanStoreRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['features'] = $this->normalizeFeatures($data['features'] ?? null);

        Plan::create($data);

        return redirect()->route('admin.plans.index')->with('status', 'Plan created.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', [
            'plan' => $plan,
        ]);
    }

    public function update(PlanUpdateRequest $request, Plan $plan)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['features'] = $this->normalizeFeatures($data['features'] ?? null);

        $plan->update($data);

        return redirect()->route('admin.plans.index')->with('status', 'Plan updated.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('admin.plans.index')->with('status', 'Plan deleted.');
    }

    protected function normalizeFeatures($features): ?array
    {
        if ($features === null) {
            return null;
        }

        if (is_array($features)) {
            return $features;
        }

        $decoded = json_decode($features, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        $lines = array_filter(array_map('trim', preg_split('/[\r\n,]+/', (string) $features)));

        return $lines ?: null;
    }
}
