<?php

namespace App\Http\Controllers;

use App\Services\LookupService;
use Illuminate\Http\Request;

class LookupController extends Controller
{
    protected LookupService $lookupService;

    public function __construct(LookupService $lookupService)
    {
        $this->lookupService = $lookupService;
    }

    public function lookup(Request $request)
    {
        if (filled($request->get('type')) && ($request->filled('username') || $request->filled('id'))) {
            $type = $request->get('type');
            $params = $request->only(['username', 'id']);

            return $this->lookupService->lookup($type, $params);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'The `type` parameter is required, and either `username` or `id` must be provided.',
                'code' => 400,
            ], 400);
        }
    }
}
