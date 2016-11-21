<?php

namespace App\Http\Controllers\Api\Admin;

use App\Geolite\Location;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class LocationsController extends ApiController
{
    public function index()
    {
        $countries = Location::query()
            ->distinct()
            ->orderBy('country')
            ->where('country', '!=', '')
            ->get(['country'])
            ->pluck('country');

        $countries = $countries->map(function ($value) {
            return [
                'name' => $value,
                'type' => 'country',
            ];
        });

        return $this->jsonResponse(['data' => $countries]);
    }

    public function show(Request $request)
    {
        $name   = $request->get('name');
        $type   = $request->get('type');
        $parent = $request->get('parent');

        if ($type == 'country') {
            $expandTo  = 'state';
            $newParent = [
                'country' => $name,
            ];
        } elseif ($type == 'state') {
            $expandTo  = 'city';
            $newParent = [
                'country' => $parent['country'],
                'state'   => $name,
            ];
        }

        $locations = Location::query()
            ->distinct()
            ->orderBy($expandTo)
            ->where($expandTo, '!=', '')
            ->where($type, $name)
            ->get([$expandTo])
            ->pluck($expandTo);

        $locations = $locations->map(function ($value) use ($expandTo, $newParent) {
            return [
                'name'   => $value,
                'type'   => $expandTo,
                'parent' => $newParent,
            ];
        });

        return $this->jsonResponse(['data' => $locations]);
    }
}
