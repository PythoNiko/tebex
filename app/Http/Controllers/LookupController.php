<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\LookupService;
use Illuminate\Http\Request;

/**
 * Class LookupController
 *
 * @package App\Http\Controllers
 */
class LookupController extends Controller
{
    protected LookupService $lookupService;

    public function __construct(LookupService $lookupService)
    {
        $this->lookupService = $lookupService;
    }

    public function lookup(Request $request)
    {
        $type = $request->get('type');
        $params = $request->only(['username', 'id']);

        return $this->lookupService->lookup($type, $params);
    }
}
