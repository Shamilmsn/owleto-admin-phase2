<?php

namespace App\Http\Controllers\API;

use App\Models\Market;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class FieldController
 * @package App\Http\Controllers\API
 */

class GlobalSearchController extends Controller
{


    /**
     * Display a listing of the Field.
     * GET|HEAD /fields
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
           $products = Product::query()
               ->with('field')
               ->where('base_name', 'LIKE', '%' . $request->get('query') . '%')
               ->orWhere('variant_name', 'LIKE', '%' . $request->get('query') . '%')
               ->get();
           $data['products'] = $products;

           $market = Market::query()
               ->with('mediaProfilePic', 'mediaCoverPic', 'images')
               ->where('name', 'LIKE', "%" . $request->input('query'). "%")
               ->get();

           $data['markets'] = $market;


        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($data, 'Global search retrieved successfully');
    }
}
