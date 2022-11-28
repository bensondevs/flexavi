<?php

namespace App\Http\Controllers\Api\Company\Search;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Search\GeneralSearchRequest as Request;
use App\Services\Algolia\AlgoliaService;
use Illuminate\Http\Response;

class GeneralSearchController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $keyword = $request->get("keyword") ?? "";
        $data = AlgoliaService::search($keyword);
        return response()->json(compact("data"));
    }
}
