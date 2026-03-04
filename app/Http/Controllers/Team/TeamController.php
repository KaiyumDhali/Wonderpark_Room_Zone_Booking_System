<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{

    // public function home()
    // {
    //     $teams = Team::orderby('id', 'desc')->first();
    //     return view('home.team', compact('teams'));
    // }

    public function index()
    {
        $data['teams'] = Team::orderBy('id', 'desc')->paginate(10);
        return view('pages.team.index', $data)
            ->with('i', (request()->input('page', 1) - 1) * 3);
    }


    public function create()
    {
        return view('pages.team.create');
    }


    public function store(Request $request)
    {
        $team = new Team();
        
        if ($request->hasfile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            $image = $request->file('image');
            $randomNumber = mt_rand(100000, 999999);
            $extension = $image->getClientOriginalExtension();
            $name = date('d-m-Y-H-i-s') . '-' . $randomNumber . '.' . $extension;
            $image->storeAs('public/images/team', $name);
            $team->image = 'images/team/' . $name;
        }

        $team->name = $request->name;
        $team->designation = $request->designation;
        $team->start_date = $request->start_date;
        $team->description = $request->description;

        $team->fb_url = $request->fb_url;
        $team->twitter_url = $request->twitter_url;
        $team->linkdin_url = $request->linkdin_url;
        $team->instagram_url = $request->instagram_url;

        $team->order = $request->order;
        $team->status = $request->status;
        $team->save();

        return redirect()->route('team.index')->with('success', 'Created sucessfully.');
    }


    public function show($id)
    {
        $data['team'] = Team::find($id);
        return view('pages.team.show', $data);
    }


    public function edit($id)
    {
        $data['team'] = Team::find($id);
        return view('pages.team.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $team = Team::find($id);

        if ($request->hasfile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            $image = $request->file('image');
            $randomNumber = mt_rand(100000, 999999);
            $extension = $image->getClientOriginalExtension();
            $name = date('d-m-Y-H-i-s') . '-' . $randomNumber . '.' . $extension;
            $image->storeAs('public/images/team', $name);
            $team->image = 'images/team/' . $name;
        }

        $team->name = $request->name;
        $team->designation = $request->designation;
        $team->start_date = $request->start_date;
        $team->description = $request->description;

        $team->fb_url = $request->fb_url;
        $team->twitter_url = $request->twitter_url;
        $team->linkdin_url = $request->linkdin_url;
        $team->instagram_url = $request->instagram_url;

        $team->order = $request->order;
        $team->status = $request->status;
        $team->save();

        return redirect()->route('team.index')->with('success', 'Update sucessfully.');
    }

    // Logo Storage Destroy
    public function imageDestroy($id)
    {
        $team = Team::find($id);
        if ($team->image != Null) {
            Storage::disk('public')->delete($team->image);
            $team->image = Null;
            $team->save();
        }
        return redirect()->route('team.edit', $team->id)->with('success', 'Image successfully deleted.');
    }


    public function destroy($id)
    {
        $team = Team::find($id);
        if ($team->image != Null) {
            Storage::disk('public')->delete($team->image);
        }
        $team->delete();
        return redirect()->route('team.index')->with('success', 'Successfully deleted.');
    }
}
