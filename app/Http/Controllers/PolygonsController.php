<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolygonsModel;

class PolygonsController extends Controller
{
    public function __construct()
    {
        $this->polygons = new PolygonsModel();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // Validation request
         $request->validate([
            'name' => 'required|unique:polygons,name',
            'description' => 'required',
            'geom_polygon' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif, svg|max:50',
        ],
        [
            'name.required' => 'Name is required',
            'name.unique' => 'Name already exists',
            'description.required' => 'Description is required',
            'geom_polygon.required' => 'Geometry polygon is required',
        ]
        );

        // Create Image Directory if nots exists
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
         }

         // Get Image File
         if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
          } else {
            $name_image = null;
          }

        $data = [
            'geom' => $request->geom_polygon,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];


        // Create data
        if (!$this->polygons->create($data)) {
            return redirect()->route('map')->with('error', 'Polygon failed to added');
        }

        // Redirect to map
        return redirect()->route('map')->with('success', 'Polygon has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'title' => 'Edit Polygons',
            'id' => $id,
        ];

        return view('edit-polygon', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation request
        $request->validate([
            'name' => 'required|unique:polygons,name,' .$id,
            'description' => 'required',
            'geom_polygon' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif, svg|max:50',
        ],
        [
            'name.required' => 'Name is required',
            'name.unique' => 'Name already exists',
            'description.required' => 'Description is required',
            'geom_polygon.required' => 'Geometry polygon is required',
        ]
        );

        // Create Image Directory if nots exists
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
         }

         //Get all image file name
         $old_image = $this->polygons->find($id)->image;

         // Get Image File
         if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);

            // Delete old image file
            if ($old_image !=null) {
                if (file_exists('. /storage/images/' . $old_image)) {
            unlink(' ./storage/images/' .$old_image) ;
         }
         }
          } else {
            $name_image = $old_image;
          }

        $data = [
            'geom' => $request->geom_polygon,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];


        // Update data
        if (!$this->polygons->find($id)->update($data)) {
            return redirect()->route('map')->with('error', 'polygon failed to update');
        }

        // Redirect to map
        return redirect()->route('map')->with('success', 'polygon has been updated');

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagefile = $this->polygons->find($id)->image;

        if (!$this->polygons->destroy($id)) {
            return redirect()->route('map')->with('eror', 'Polygons failed to delete');
        }

        //Delete image file
        if ($imagefile != null) {
            if (file_exists('./storage/images/' . $imagefile)) {
                unlink('./storage/images/' .$imagefile);
            }
        }

        return redirect()->route('map')->with('success', 'Polygons has been deleted');

    }
}
