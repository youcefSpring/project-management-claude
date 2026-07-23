<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\LandingContent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LandingContentController extends Controller
{
    /**
     * Edit the landing copy for one language at a time.
     */
    public function index(Request $request)
    {
        $locale = $request->query('locale', 'en');

        abort_unless(in_array($locale, LandingContent::LOCALES, true), 404);

        $groups = LandingContent::editableKeys();
        $overrides = LandingContent::where('locale', $locale)->pluck('value', 'key');

        return view('superadmin.landing.index', compact('locale', 'groups', 'overrides'));
    }

    /**
     * Save the copy. An empty field falls back to the translation file.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'locale' => ['required', Rule::in(LandingContent::LOCALES)],
            'content' => ['array'],
            'content.*' => ['nullable', 'string', 'max:2000'],
        ]);

        $locale = $data['locale'];
        $allowed = collect(LandingContent::editableKeys())->flatten()->all();

        foreach ($data['content'] ?? [] as $key => $value) {
            if (! in_array($key, $allowed, true)) {
                continue;
            }

            $value = trim((string) $value);

            if ($value === '') {
                LandingContent::where('key', $key)->where('locale', $locale)->delete();

                continue;
            }

            LandingContent::updateOrCreate(
                ['key' => $key, 'locale' => $locale],
                ['value' => $value]
            );
        }

        LandingContent::flushCache();

        return redirect()->route('superadmin.landing.index', ['locale' => $locale])
            ->with('success', __('app.landing_admin.updated'));
    }

    /**
     * Drop every override for a language and go back to the translation files.
     */
    public function reset(Request $request)
    {
        $data = $request->validate([
            'locale' => ['required', Rule::in(LandingContent::LOCALES)],
        ]);

        LandingContent::where('locale', $data['locale'])->delete();
        LandingContent::flushCache();

        return redirect()->route('superadmin.landing.index', ['locale' => $data['locale']])
            ->with('success', __('app.landing_admin.reset'));
    }
}
