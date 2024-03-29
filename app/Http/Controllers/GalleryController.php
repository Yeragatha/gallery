<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'caption' => 'required',
            'picture' => 'required|image|file|max:5000',
        ]);

        if ($request->file('picture')) {
            $oriName = $request->file('picture')->getClientOriginalName();
            $validated['picture'] = md5(time()) . preg_replace('/\s+/', '', $oriName);
            $request->picture->move(public_path('photo'), $validated['picture']);
        } else $validated['picture'] = 'default.jpg';


        Gallery::create($validated);
        return redirect()
            ->route('dashboard')
            ->with('success', 'Success Add Picture');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'caption' => 'required',
            'picture' => 'image|file|max:5000',
        ]);

        if ($request->file('picture')) {
            if ($gallery->getOriginal()['picture'] !== 'default.jpg') {
                unlink("photo/" . $gallery->getOriginal()['picture']);
            }
            $oriName = $request->file('picture')->getClientOriginalName();
            $validated['picture'] = md5(time()) . preg_replace('/\s+/', '', $oriName);
            $request->picture->move(public_path('photo'), $validated['picture']);
        }

        Gallery::where('id', $gallery->id)->update($validated);
        return redirect()
            ->route('dashboard')
            ->with('success', 'Success Edit Data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        if ($gallery->getOriginal()['picture'] !== 'default.jpg') {
            unlink("photo/" . $gallery->getOriginal()['picture']);
        }

        Gallery::destroy($gallery->id);
        return redirect()
            ->route('dashboard')
            ->with('success', 'success remove your post');
    }
}
