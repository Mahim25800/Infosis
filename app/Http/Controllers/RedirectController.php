<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\RedirectResponse;

class RedirectController extends Controller
{
    public function __invoke(string $shortCode): RedirectResponse
    {
        $url = Url::where('short_code', $shortCode)->firstOrFail();

        // increment() is a single database query, so simultaneous visits are counted safely.
        $url->increment('click_count');

        return redirect()->away($url->original_url);
    }
}
