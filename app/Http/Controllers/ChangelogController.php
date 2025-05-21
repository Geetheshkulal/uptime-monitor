<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Changelog;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class ChangelogController extends Controller
{
    public function ChangelogPage(Request $request)
{
    $search = $request->input('search');
    $types = $request->input('types', []);
    
    $query = Changelog::orderBy('release_date', 'desc');
    
    // Search filter
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('version', 'like', "%{$search}%")
              ->orWhere('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }
    
    // Type filter
    if (!empty($types)) {
        $query->where(function($q) use ($types) {
            foreach ($types as $type) {
                $q->orWhere('type', 'like', "%{$type}%");
            }
        });
    }
    
    $changelogs = $query->paginate(10);
    $latestDate = Carbon::parse(Changelog::max('release_date'));
    
    return view('pages.Changelog', compact('changelogs', 'latestDate', 'search', 'types'));
}
    
    public function AddChangelog(Request $request)
{
    $request->validate([
        'versionNumber' => 'required|unique:changelogs,version',
        'versionTitle' => 'required',
        'description' => 'required',
        'releaseDate' => 'required',
        'types' => 'required|array|min:1',
        'types.*' => 'in:new,improved,fixed'
    ]);

    $types = implode(',', $request->types);

    Changelog::create([
        'version' => $request->versionNumber,
        'title' => $request->versionTitle,
        'description' => $request->description,
        'release_date' => $request->releaseDate,
        'type' => $types
    ]);

    return redirect()->back()->with('success', 'Changelog added successfully.');
}

    public function destroy($id)
    {
        $changelog = Changelog::findOrFail($id);
        $changelog->delete();

        return redirect()->back()->with('success', 'Changelog deleted successfully.');
    }

    public function update(Request $request, Changelog $changelog)
{
    $validated = $request->validate([
        'editversionNumber' => [
            'required',
            'string',
            'max:255',
            Rule::unique('changelogs', 'version')->ignore($changelog->id,'id'),
        ],
        'editversionTitle' => 'required|string|max:255',
        'editdescription' => 'required|string',
        'edittypes' => 'required|array|min:1',
        'edittypes.*' => 'in:new,improved,fixed'
    ]);

    $changelog->version = $validated['editversionNumber'];
    $changelog->title = $validated['editversionTitle'];
    $changelog->description = $validated['editdescription'];
    $changelog->type = implode(',', $validated['edittypes']);
    $changelog->save();

    return redirect()->back()->with('success', 'Changelog updated successfully.');
}



}
