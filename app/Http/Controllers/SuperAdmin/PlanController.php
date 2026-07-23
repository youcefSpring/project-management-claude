<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    /**
     * All plans, published or not.
     */
    public function index()
    {
        $plans = Plan::orderBy('sort_order')->orderBy('id')->get();

        return view('superadmin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('superadmin.plans.form', [
            'plan' => new Plan([
                'currency' => 'DA',
                'cta_type' => 'register',
                'is_active' => true,
                'sort_order' => (Plan::max('sort_order') ?? 0) + 1,
                'translations' => [],
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $plan = Plan::create($data + [
            'slug' => Str::slug($request->input('translations.en.name') ?: 'plan-'.Str::random(5)),
        ]);

        return redirect()->route('superadmin.plans.index')
            ->with('success', __('Plan created. It is live on the landing page.'));
    }

    public function edit(Plan $plan)
    {
        return view('superadmin.plans.form', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $plan->update($this->validated($request));

        return redirect()->route('superadmin.plans.index')
            ->with('success', __('Plan updated.'));
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('superadmin.plans.index')
            ->with('success', __('Plan deleted.'));
    }

    /**
     * Shared validation for create and update.
     */
    private function validated(Request $request): array
    {
        $rules = [
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'cta_type' => ['required', Rule::in(['register', 'contact'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];

        foreach (Plan::LOCALES as $locale) {
            $rules["translations.$locale.name"] = [$locale === 'en' ? 'required' : 'nullable', 'string', 'max:80'];
            $rules["translations.$locale.audience"] = ['nullable', 'string', 'max:120'];
            $rules["translations.$locale.price_label"] = ['nullable', 'string', 'max:60'];
            $rules["translations.$locale.features"] = ['nullable', 'string', 'max:2000'];
        }

        $validated = $request->validate($rules);

        $translations = [];

        foreach (Plan::LOCALES as $locale) {
            $input = $validated['translations'][$locale] ?? [];

            $translations[$locale] = [
                'name' => $input['name'] ?? '',
                'audience' => $input['audience'] ?? '',
                'price_label' => $input['price_label'] ?? '',
                // One feature per line in the form
                'features' => collect(preg_split('/\r\n|\r|\n/', (string) ($input['features'] ?? '')))
                    ->map(fn ($line) => trim($line))
                    ->filter()
                    ->values()
                    ->all(),
            ];
        }

        return [
            'price' => $request->boolean('is_free') ? null : ($validated['price'] ?? null),
            'currency' => $validated['currency'],
            'is_free' => $request->boolean('is_free'),
            'cta_type' => $validated['cta_type'],
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
            'translations' => $translations,
        ];
    }
}
