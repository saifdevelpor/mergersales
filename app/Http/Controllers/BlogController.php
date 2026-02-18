<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'Admin') {
            $blogs = Blog::latest()->get();
        } else {
            $blogs = Blog::where('user_id', auth()->id())->latest()->get();
        }

        return view('blog.list', compact('blogs'));
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'details' => 'required|string',
            // ✅ PDF remove (image rule PDF accept nahi karta)
            'image'   => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $blog = new Blog();
        $blog->user_id  = auth()->id();
        $blog->details = htmlspecialchars_decode($request->details);

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/blogs'), $imageName);
            $blog->image = 'uploads/blogs/' . $imageName;
        }

        $blog->save();

        return redirect()->back()->with('success', 'Blog saved successfully!');
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'details' => 'required|string',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $blog->details = htmlspecialchars_decode($request->details);

        if ($request->hasFile('image')) {
            // old delete
            if ($blog->image && file_exists(public_path($blog->image))) {
                @unlink(public_path($blog->image));
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/blogs'), $imageName);
            $blog->image = 'uploads/blogs/' . $imageName;
        }

        $blog->save();

        return redirect()->back()->with('success', 'Blog updated successfully!');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image && file_exists(public_path($blog->image))) {
            @unlink(public_path($blog->image));
        }

        $blog->delete();

        return redirect()->back()->with('success', 'Blog deleted successfully.');
    }
}
