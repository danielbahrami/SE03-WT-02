<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdoptionController extends Controller
{
    public function create()
    {
        return view('adoptions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required'],
            'description' => ['required'],
            'image'       => ['file', 'image']
        ]);


        $adoption = new Adoption();
        if ($request->has('image'))
        {
            $filename = Str::random(32) . "." . $request->file('image')->extension();
            $request->file('image')->move('imgs/uploads', $filename);
            $adoption->image_path = "imgs/uploads/$filename";
        }
        else
            $adoption->image_path = "imgs/demo/4.jpg";
        $adoption->name        = $validated['name'];
        $adoption->description = $validated['description'];
        $adoption->listed_by   = auth()->id();
        $adoption->save();
        return redirect('/') -> with('success', 'Post for ' . $adoption -> name . ' created successfully');


        /*
        |-----------------------------------------------------------------------
        | Task 4 User, step 5.
        | The $adoption variable should be assigned to the logged user.
        | This is done using the listed_by field from the user column in the database.
        |-----------------------------------------------------------------------
        */

    }

    public function show(Adoption $adoption)
    {
        return view('adoptions.details', ['adoption' => $adoption]);
    }

    public function adopt(Adoption $adoption)
    {
        if (auth()->user()->cannot('update',$adoption))
        {
            abort(403);
        }
        $adoption->adopted_by = auth()->id();
        $adoption->saveOrFail();

        return redirect()->home()->with('success', "Pet $adoption->name adopted successfully");
        /*
                |-----------------------------------------------------------------------
                | Task 5 User, step 6. You should assing $adoption
                | The $adoption variable should be assigned to the logged user.
                | This is done using the adopted_by field from the user column in the database.
                |-----------------------------------------------------------------------
                */


    }


    public function mine()
    {
        $adoptions = Auth::user()->adoptions;
        return view('adoptions.list', ['adoptions' => $adoptions, 'header' => 'My Adoptions']);
    }
}
