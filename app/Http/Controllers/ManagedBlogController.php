<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ManagedBlogController extends Controller
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
            'title' => 'nullable|string|max:255',
            'details' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:1000',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'og_image' => 'nullable|string|max:255',
            'featured_image_alt' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $blog = new Blog();
        $blog->user_id = auth()->id();
        $blog->title = $request->filled('title')
            ? trim((string) $request->title)
            : Str::limit(strip_tags((string) $request->details), 255, '');
        $blog->details = htmlspecialchars_decode((string) $request->details);
        $blog->seo_title = $request->filled('seo_title') ? trim((string) $request->seo_title) : null;
        $blog->seo_description = $request->filled('seo_description') ? trim((string) $request->seo_description) : null;
        $blog->slug = $request->filled('slug') ? trim((string) $request->slug) : null;
        $blog->og_image = $request->filled('og_image') ? trim((string) $request->og_image) : null;
        $blog->featured_image_alt = $request->filled('featured_image_alt') ? trim((string) $request->featured_image_alt) : null;

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/blogs'), $imageName);
            $blog->image = 'uploads/blogs/' . $imageName;

            if (! $blog->og_image) {
                $blog->og_image = $blog->image;
            }
        }

        $blog->save();

        return redirect()->back()->with('success', 'Blog saved successfully!');
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail(d_id($id) ?? $id);

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'details' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:1000',
            'slug' => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'og_image' => 'nullable|string|max:255',
            'featured_image_alt' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $blog->title = $request->filled('title')
            ? trim((string) $request->title)
            : Str::limit(strip_tags((string) $request->details), 255, '');
        $blog->details = htmlspecialchars_decode((string) $request->details);
        $blog->seo_title = $request->filled('seo_title') ? trim((string) $request->seo_title) : null;
        $blog->seo_description = $request->filled('seo_description') ? trim((string) $request->seo_description) : null;
        $blog->slug = $request->filled('slug') ? trim((string) $request->slug) : $blog->slug;
        $blog->og_image = $request->filled('og_image') ? trim((string) $request->og_image) : $blog->og_image;
        $blog->featured_image_alt = $request->filled('featured_image_alt') ? trim((string) $request->featured_image_alt) : null;

        if ($request->hasFile('image')) {
            if ($blog->image && file_exists(public_path($blog->image))) {
                @unlink(public_path($blog->image));
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/blogs'), $imageName);
            $blog->image = 'uploads/blogs/' . $imageName;

            if (! $request->filled('og_image')) {
                $blog->og_image = $blog->image;
            }
        }

        $blog->save();

        return redirect()->back()->with('success', 'Blog updated successfully!');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail(d_id($id) ?? $id);

        if ($blog->image && file_exists(public_path($blog->image))) {
            @unlink(public_path($blog->image));
        }

        $blog->delete();

        return redirect()->back()->with('success', 'Blog deleted successfully.');
    }
}
