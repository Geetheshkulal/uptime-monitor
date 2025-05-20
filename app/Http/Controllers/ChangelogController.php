<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Changelog;
use Carbon\Carbon;
class ChangelogController extends Controller
{
    //
    public function ChangelogPage(){
        $changelogs =  Changelog::orderBy('created_at', 'desc')->paginate(10);
        $latestDate = Carbon::parse(Changelog::max('created_at'));
        return view('pages.Changelog',compact('changelogs','latestDate'));
    }
    
    public function AddChangelog(Request $request)
    {
        $request->validate([
            'versionNumber' => 'required',
            'versionTitle' => 'required',
            'description' => 'required'
        ]);
    
        Changelog::create([
            'version' => $request->versionNumber,
            'title' => $request->versionTitle,
            'description'=>$request->description
        ]);
    
        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    public function destroy($id)
    {
        $changelog = Changelog::findOrFail($id);
        $changelog->delete();

        return redirect()->back()->with('success', 'Changelog deleted successfully.');
    }

    public function update(Request $request, Changelog $changelog)
    {
        // Debug to check incoming data
        // dd($request->all());

        $validated = $request->validate([
            'editversionNumber' => 'required|string|max:255|unique:changelogs,version',
            'editversionTitle' => 'required|string|max:255',
            'editdescription' => 'required|string', // assuming your input name is description
        ]);

        $changelog->version = $validated['editversionNumber'];
        $changelog->title = $validated['editversionTitle'];
        $changelog->description = $validated['editdescription']; // HTML content from Quill
        $changelog->save();

        return redirect()->back()->with('success', 'Changelog updated successfully.');
    }



}
