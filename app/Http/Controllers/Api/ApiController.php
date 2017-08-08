<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\DataArraySerializer;

class ApiController extends Controller
{
    /** @var User */
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();

            return $next($request);
        });
    }

    public function eagerLoads()
    {
        $request  = request();
        $includes = $request->get('include');

        if ($includes === null) {
            return;
        }

        $fractal = new Manager;

        return $fractal->parseIncludes($includes)->getRequestedIncludes();
    }

    /**
     * Create a JSON response from a collection.
     *
     * @param array|ArrayIterator                          $collection
     * @param \League\Fractal\TransformerAbstract|callable $transformer
     * @param int                                          $status
     * @param array                                        $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function collectionResponse($collection, $transformer, $status = 200, $headers = [])
    {
        $resource = new Collection($collection, $transformer);

        return $this->resourceResponse($resource, $status, $headers);
    }

    /**
     * Create a JSON response from a paginated collection.
     *
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator
     * @param \League\Fractal\TransformerAbstract|callable          $transformer
     * @param int                                                   $status
     * @param array                                                 $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginatedCollectionResponse($paginator, $transformer, $status = 200, $headers = [])
    {
        $resource = new Collection($paginator->items(), $transformer);

        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $this->resourceResponse($resource, $status, $headers);
    }

    /**
     * Create a JSON response from a single item.
     *
     * @param mixed                                        $item
     * @param \League\Fractal\TransformerAbstract|callable $transformer
     * @param int                                          $status
     * @param array                                        $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function itemResponse($item, $transformer, $status = 200, $headers = [])
    {
        $resource = new Item($item, $transformer);

        return $this->resourceResponse($resource, $status, $headers);
    }

    /**
     * Create a JSON response from a resource.
     *
     * @param \League\Fractal\Resource\ResourceInterface $resource
     * @param int                                        $status
     * @param array                                      $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resourceResponse(ResourceInterface $resource, $status = 200, $headers = [])
    {
        $fractal = new Manager();
        $fractal->setSerializer(new DataArraySerializer);

        if (($request = app('request')) && ($include = $request->get('include'))) {
            $fractal->parseIncludes($include);
        }

        return $this->jsonResponse($fractal->createData($resource)->toArray(), $status, $headers);
    }

    /**
     * Create a JSON response.
     *
     * @param string|array $data
     * @param int          $status
     * @param array        $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonResponse($data, $status = 200, $headers = [])
    {
        return response()->json($data, $status, $headers);
    }

    /**
     * Create an error JSON response.
     *
     * @param string|array $errors
     * @param int          $status
     * @param array        $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($errors, $status = 500, $headers = [])
    {
        if (! is_array($errors)) {
            $errors = [['message' => $errors, 'code' => 0]];
        }

        return $this->jsonResponse(compact('errors'), $status, $headers);
    }

    /**
     * Get the paginator for the given Eloquent query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($query, $perPage = 100)
    {
        return $query->paginate($perPage);
    }
}
