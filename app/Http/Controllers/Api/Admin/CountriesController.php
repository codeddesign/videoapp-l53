<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Transformers\CountryTransformer;
use App\Transformers\SubdivisionTransformer;
use Illuminate\Http\Request;
use Phine\Country\Loader\Loader;

class CountriesController extends ApiController
{
    public function index()
    {
        $loader = new Loader();
        $countries = $loader->loadCountries();

        return $this->collectionResponse($countries, new CountryTransformer);
    }

    public function show(Request $request)
    {
        $loader = new Loader();

        $subDivisions = $loader->loadSubdivisions($request->get('code'));

        return $this->collectionResponse($subDivisions, new SubdivisionTransformer);
    }
}
