<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blog = Blog::orderby('id', 'desc')->get();
        return view('pages.blog.index', compact('blog'));
    }

    public function create()
    {
        return view('pages.blog.create');
    }

    public function store(Request $request)
    {
        $blog = new Blog();

        if ($request->hasfile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            $image = $request->file('image');
            $randomNumber = mt_rand(100000, 999999);
            $extension = $image->getClientOriginalExtension();
            $name = date('d-m-Y-H-i-s') . '-' . $randomNumber . '.' . $extension;
            $image->storeAs('public/images/blog', $name);
            $blog->image = 'images/blog/' . $name;
        }

        $blog->title = $request->title;
        $blog->type = $request->type;
        $blog->description = $request->description;
        $blog->status = $request->status;

        $blog->save();

        return redirect()->route('blog.index')->with('success', 'Created successfully.');
    }

    public function show($id)
    {
        $data['blog'] = Blog::find($id);
        return view('pages.blog.show', $data);
    }

    public function edit($id)
    {
        $data['blog'] = Blog::find($id);
        return view('pages.blog.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);

        if ($request->hasfile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            $image = $request->file('image');
            $randomNumber = mt_rand(100000, 999999);
            $extension = $image->getClientOriginalExtension();
            $name = date('d-m-Y-H-i-s') . '-' . $randomNumber . '.' . $extension;
            $image->storeAs('public/images/blog', $name);
            $blog->image = 'images/blog/' . $name;
        }

        $blog->title = $request->title;
        $blog->type = $request->type;
        $blog->description = $request->description;
        $blog->status = $request->status;
        $blog->save();

        return redirect()->route('blog.index')->with('success', 'Created sucessfully.');
    }

    // Image Storage Destroy
    public function imageDestroy($id)
    {
        $blog = Blog::find($id);
        if ($blog->image != null) {
            // dd($Blog->image);
            Storage::disk('public')->delete($blog->image);
            $blog->image = null;
            $blog->save();
        }
        return redirect()->route('blog.edit', $blog->id)->with('success', 'Image successfully deleted.');
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);
        if ($blog->image != null) {
            Storage::disk('public')->delete($blog->image);
        }
        $blog->delete();
        return redirect()->route('blog.index')->with('success', 'Successfully deleted.');
    }
}
