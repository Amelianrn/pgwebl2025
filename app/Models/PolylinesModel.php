<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PolylinesModel extends Model
{
    protected $table = 'polylines';

    protected $guarded = ['id'];

    public function gejson_polylines()

{
    $polylines = $this
    ->select(DB::raw('st_asgeojson(geom) as geom, name, description,image,
    st_length(geom, true) as length_m, st_length(geom, true)/1000 as length_km, created_at, updated_at'))
    ->get();

    $geojson = [
        'type' => 'FeatureCollection',
        'features' =>[],
    ];

    foreach ($polylines as $polyline) {
        $feature = [
            'type' => 'Feature',
            'geometry' => json_decode($polyline->geom),
            'properties' => [
                'name' => $polyline->name,
                'description' => $polyline->description,
                'image' => $polyline->image,
                'length_m' => $polyline->length_m,
                'length_km' => $polyline->length_km,
                'created_at' => $polyline->created_at,
                'updated_at' => $polyline->updated_at,
            ],
        ];

        array_push($geojson['features'],$feature);
    }
        return $geojson;
    }
}
