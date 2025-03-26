<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use Illuminate\Http\Request;
use App\Models\PolygonsModel;
use App\Models\PolylinesModel;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->points = new PointsModel();
        $this->polylines = new PolylinesModel();
        $this->polygons = new PolygonsModel();
    }

    public function points()
    {
        $points = $this->points->gejson_points();

        return response()->json($points);
    }

    public function polylines()
    {
        $polylines = $this->polylines->gejson_polylines();

        return response()->json($polylines);
    }
    

    public function polygons()
{
    $polygons = $this->polygons->gejson_polygons();

    return response()->json($polygons);
}

}
