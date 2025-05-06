<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PointsModel extends Model
{
    protected $table = 'points';

    protected $guarded = ['id'];

    public function gejson_points()

{
    $points = $this
    ->select(DB::raw('id, st_asgeojson(geom) as geom, name, description, image, created_at,
    updated_at'))
    ->get();

    $geojson = [
        'type' => 'FeatureCollection',
        'features' =>[],
    ];

    foreach ($points as $point) {
        $feature = [
            'type' => 'Feature',
            'geometry' => json_decode($point->geom),
            'properties' => [
                'id' => $point->id,
                'name' => $point->name,
                'description' => $point->description,
                'created_at' => $point->created_at,
                'image'=> $point->image,
                'updated_at' => $point->updated_at,
            ],
        ];

        array_push($geojson['features'],$feature);
    }
        return $geojson;
    }

}


