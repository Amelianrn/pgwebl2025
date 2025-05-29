<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolylinesModel;

class PolylinesController extends Controller
{
    public function __construct()
    {
        $this->polylines = new PolylinesModel();
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
            'name' => 'required|unique:polylines,name',
            'description' => 'required',
            'geom_polyline' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif, svg|max:50',
        ],
        [
            'name.required' => 'Name is required',
            'name.unique' => 'Name already exists',
            'description.required' => 'Description is required',
            'geom_polyline.required' => 'Geometry polyline is required',
        ]
        );

        // Create Image Directory if nots exists
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
         }

         // Get Image File
         if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
          } else {
            $name_image = null;
          }

        $data = [
            'geom' => $request->geom_polyline,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
             'user_id' => auth()->user()->id,
        ];


        // Create data
        if (!$this->polylines->create($data)) {
            return redirect()->route('map')->with('error', 'Polylines failed to added');
        }

        // Redirect to map
        return redirect()->route('map')->with('success', 'Polylines has been added');
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
            'title' => 'Edit Polylines',
            'id' => $id,
        ];

        return view('edit-polyline', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation request
        $request->validate([
            'name' => 'required|unique:polylines,name,' .$id,
            'description' => 'required',
            'geom_polyline' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif, svg|max:50',
        ],
        [
            'name.required' => 'Name is required',
            'name.unique' => 'Name already exists',
            'description.required' => 'Description is required',
            'geom_polyline.required' => 'Geometry polyline is required',
        ]
        );

        // Create Image Directory if nots exists
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
         }

         //Get all image file name
         $old_image = $this->polylines->find($id)->image;

         // Get Image File
         if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);

            // Delete old image file
            // Delete old image file
            if ($old_image != null) {
                $old_image_path = public_path('storage/images/' . $old_image);
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }

          } else {
            $name_image = $old_image;
          }

        $data = [
            'geom' => $request->geom_polyline,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];


        // Update data
        if (!$this->polylines->find($id)->update($data)) {
            return redirect()->route('map')->with('error', 'Polyline failed to update');
        }

        // Redirect to map
        return redirect()->route('map')->with('success', 'Polyline has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagefile = $this->polylines->find($id)->image;

        if (!$this->polylines->destroy($id)) {
            return redirect()->route('map')->with('eror', 'Polylines failed to delete');
        }

        //Delete image file
        if ($imagefile != null) {
            if (file_exists('./storage/images/' . $imagefile)) {
                unlink('./storage/images/' .$imagefile);
            }
        }

        return redirect()->route('map')->with('success', 'Polylines has been deleted');

    }
}
