<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrlRequest;
use App\Models\Url;
use App\Services\ShortCodeGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->integer('per_page', 10), 1), 100);
        $urls = $request->user()
            ->urls()
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'URLs retrieved successfully',
            'data' => [
                'items' => $urls->getCollection()->map(fn (Url $url) => $this->urlData($url)),
                'pagination' => [
                    'current_page' => $urls->currentPage(),
                    'per_page' => $urls->perPage(),
                    'total' => $urls->total(),
                    'last_page' => $urls->lastPage(),
                ],
            ],
        ]);
    }

    public function store(StoreUrlRequest $request, ShortCodeGenerator $shortCodeGenerator): JsonResponse
    {
        $data = $request->validated();
        $shortCode = $shortCodeGenerator->generate();

        $url = $request->user()->urls()->create([
            'original_url' => $data['url'],
            'short_code' => $shortCode,
            // Set this explicitly so the creation response immediately shows 0.
            'click_count' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'URL shortened successfully',
            'data' => $this->urlData($url),
        ], 201);
    }

    public function show(Request $request, Url $url): JsonResponse
    {
        if (! $this->canManage($request, 'view', $url)) {
            return $this->accessDeniedResponse();
        }

        return response()->json([
            'success' => true,
            'message' => 'URL retrieved successfully',
            'data' => $this->urlData($url),
        ]);
    }

    public function destroy(Request $request, Url $url): JsonResponse
    {
        if (! $this->canManage($request, 'delete', $url)) {
            return $this->accessDeniedResponse();
        }

        $url->delete();

        return response()->json([
            'success' => true,
            'message' => 'URL deleted successfully',
        ]);
    }

    /**
     * Keep the link response identical in every endpoint.
     */
    private function urlData(Url $url): array
    {
        return [
            'id' => $url->id,
            'original_url' => $url->original_url,
            'short_code' => $url->short_code,
            'short_url' => url($url->short_code),
            'click_count' => $url->click_count,
            'created_at' => $url->created_at,
        ];
    }

    /**
     * Ask UrlPolicy whether the current user owns this link.
     */
    private function canManage(Request $request, string $ability, Url $url): bool
    {
        return $request->user()->can($ability, $url);
    }

    private function accessDeniedResponse(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'You are not allowed to manage this URL.',
        ], 403);
    }
}
