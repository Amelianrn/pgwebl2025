<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PolygonsModel extends Model
{
    protected $table = 'polygons';

    protected $guarded = ['id'];

    public function gejson_polygons()
    {
        $polygons = $this
            ->select(DB::raw('polygons.id, ST_AsGeoJSON(polygons.geom) as geom, polygons.name, polygons.description, image,
        ST_Area(geom) as area, polygons.created_at, polygons.updated_at, polygons.user_id, users.name as user_created'))
            ->leftJoin('users', 'polygons.user_id', '=', 'users.id')
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polygons as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' =>$p -> id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'image' => $p->image,
                    'area' => round($p->area, 2), // Menampilkan luas
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                    'user_id' => $p->user_id,
                'user_created' => $p->user_created,
                ],
            ];

            array_push($geojson['features'], $feature);
        }

        return $geojson;
    }

    public function gejson_polygon($id)
    {
        $polygons = $this
            ->select(DB::raw('id, ST_AsGeoJSON(geom) as geom, name, description, image,
        ST_Area(geom) as area, created_at, updated_at'))
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polygons as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' =>$p -> id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'image' => $p->image,
                    'area' => round($p->area, 2), // Menampilkan luas
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                ],
            ];

            array_push($geojson['features'], $feature);
        }

        return $geojson;
    }

}
