<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ThemeController extends Controller
{
    /**
     * Toggle user theme
     */
    public function toggle(Request $request): JsonResponse
    {
        try {
            // Validate input
            $validated = $request->validate([
                'theme' => 'required|in:light,dark'
            ]);

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Start transaction
            $result = DB::transaction(function () use ($user, $validated) {
                // Update or create user preference
                $preference = UserPreference::updateOrCreate(
                    ['user_id' => $user->id],
                    ['theme' => $validated['theme']]
                );

                return $preference;
            });

            return response()->json([
                'success' => true,
                'message' => 'Theme berhasil diubah',
                'theme' => $result->theme,
                'user_id' => $user->id
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Theme toggle error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current user theme
     */
    public function current(): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Get theme langsung dari method
            $theme = $user->getTheme();

            return response()->json([
                'success' => true,
                'theme' => $theme,
                'user_id' => $user->id
            ]);
        } catch (\Exception $e) {
            Log::error('Get current theme error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'theme' => 'light' // fallback
            ], 500);
        }
    }

    /**
     * Test method untuk debugging
     */
    public function test(): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Not authenticated']);
            }

            // Test semua method
            $tests = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_model' => get_class($user),
                'methods' => [
                    'getTheme_exists' => method_exists($user, 'getTheme'),
                    'prefersDarkTheme_exists' => method_exists($user, 'prefersDarkTheme'),
                    'preference_exists' => method_exists($user, 'preference'),
                ],
                'preference_count' => UserPreference::where('user_id', $user->id)->count(),
                'preference_data' => UserPreference::where('user_id', $user->id)->first()?->toArray()
            ];

            // Test getTheme method
            try {
                $tests['getTheme_result'] = $user->getTheme();
            } catch (\Exception $e) {
                $tests['getTheme_error'] = $e->getMessage();
            }

            // Test prefersDarkTheme method
            try {
                $tests['prefersDarkTheme_result'] = $user->prefersDarkTheme();
            } catch (\Exception $e) {
                $tests['prefersDarkTheme_error'] = $e->getMessage();
            }

            return response()->json($tests);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
