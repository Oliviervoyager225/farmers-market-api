<?php

declare(strict_types=1);

namespace App\Domain\Setting\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class SettingController extends Controller
{
    /**
     * Return the two settings the mobile app cares about.
     * Accessible to all authenticated roles.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'kg_to_cfa_rate'       => (float) Setting::get('kg_to_cfa_rate', '1000'),
                'default_interest_rate' => (float) Setting::get('default_interest_rate', '0.30'),
            ],
        ]);
    }

    /**
     * Bulk-update settings from a flat object body.
     * Admin only (enforced in routes).
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'kg_to_cfa_rate'        => ['sometimes', 'numeric', 'min:1'],
            'default_interest_rate' => ['sometimes', 'numeric', 'min:0', 'max:1'],
        ]);

        if ($request->has('kg_to_cfa_rate')) {
            Setting::set('kg_to_cfa_rate', (string) $request->input('kg_to_cfa_rate'));
        }

        if ($request->has('default_interest_rate')) {
            Setting::set('default_interest_rate', (string) $request->input('default_interest_rate'));
        }

        return response()->json([
            'message' => 'Paramètres mis à jour.',
            'data'    => [
                'kg_to_cfa_rate'        => (float) Setting::get('kg_to_cfa_rate', '1000'),
                'default_interest_rate' => (float) Setting::get('default_interest_rate', '0.30'),
            ],
        ]);
    }

    /** Per-key update kept for backward compatibility with admin panel. */
    public function update(Request $request, string $key): JsonResponse
    {
        $request->validate([
            'value' => ['required', 'string', 'max:255'],
        ]);

        Setting::set($key, $request->input('value'));

        return response()->json([
            'message' => 'Paramètre mis à jour.',
            'data'    => [
                'key'   => $key,
                'value' => $request->input('value'),
            ],
        ]);
    }
}
