<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Photo;

class PhotoControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $photos = Photo::all();
        return response()->json($photos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $this->validate($request, [
            'photo' => 'required|image|max:1024',
            'description' => 'required'
        ]);

        //upload
        $request->file('photo')->store('photos', 'public');

        //save to db
        $photo = Photo::create([
            'filename' => $request->file('photo')->hashName(),
            'description' => $data['description'],
        ]);

        return response()->json($photo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $photo = Photo::find($id);
        if (!$photo) {
            # code...
            return response()->json([
                'message' => 'Photo not Found'
            ], 404);
        }
        return response()->json($photo, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = $this->validate($request, [
            'description' => 'required',
        ]);

        //photo db checking
        $photo = Photo::find($id);

        if (!$photo) {
            # code...
            return response()->json([
                'message' => "Photo not found."
            ], 404);
        }

        //updating photo
        $photo->description = $data['description'];
        $photo->save();

        //returning response
        return response()->json($photo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        //photo db checking
        $photo = Photo::find($id);

        if (!$photo) {
            # code...
            return response()->json([
                'message' => "Photo not found."
            ], 404);
        }

        $photo->delete();
        return response('delete success', 204);
    }
}
