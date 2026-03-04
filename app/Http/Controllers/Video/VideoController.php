<?php

namespace App\Http\Controllers\Video;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;




class VideoController extends Controller
{

    public function index()
    {
        $videos = Video::orderby('id', 'asc')->get();
        return view('pages.video.index', compact('videos'));
    }



    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $request->validate([
            'link' => 'nullable',
        ]);

        Video::create([
            'link' => $request->link,
        ]);

        return back()->with('success', 'Video save successfully.');
    }


    public function show(Video $video)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        //
    }


    public function update(Request $request, Video $video)
    {
        //
    }


    public function destroy(Video $video)
    {
        //
    }


    public function videoDestroy($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();

        return redirect()->back()->with('success', 'Video deleted successfully.');
    }
}
