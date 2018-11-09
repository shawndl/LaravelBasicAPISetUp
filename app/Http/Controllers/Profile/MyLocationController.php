<?php

namespace App\Http\Controllers\Profile;

use App\Http\Resources\Location\LocationResource;
use App\Location;
use App\Traits\Controllers\JsonResponseTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MyLocationController extends Controller
{
    use JsonResponseTrait;

    /**
     * @var Location
     */
    protected $locations;

    /**
     * MyLocationController constructor.
     * @param Location $locations
     */
    public function __construct(Location $locations)
    {
        $this->locations = $locations;
    }

    /**
     * get a new location
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            $locations = $this->locations
                ->with('image', 'type', 'feedback')
                ->where('user_id', auth()->id())
                ->get();
        } catch (\Exception $exception) {
            return $this->processingError($exception);
        }

        return LocationResource::collection($locations);
    }
}
