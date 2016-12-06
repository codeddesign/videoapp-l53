<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\Models\GlobalOption;
use App\Transformers\GlobalOptionTransformer;

class GlobalOptionsController extends ApiController
{
    public function index()
    {
        $globalOptions = GlobalOption::all();

        return $this->collectionResponse($globalOptions, new GlobalOptionTransformer);
    }

    public function update(Request $request)
    {
        foreach ($request->all() as $option => $value) {
            $option = snake_case($option);
            $globalOption = GlobalOption::where('option', $option)->first();

            if (! $globalOption) {
                $globalOption         = new GlobalOption;
                $globalOption->option = $option;
            }

            $globalOption->value = $value;
            $globalOption->save();
        }

        $globalOptions = GlobalOption::all();

        return $this->collectionResponse($globalOptions, new GlobalOptionTransformer);
    }
}
