<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EditTemplateController extends Controller
{
    //
    public function EditTemplatePage()
    {
        $templates = Template::all();

        return view('pages.admin.EditTemplate',compact('templates'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        Log::info('EditTemplateController@store called with request: ' . json_encode($request->all()));
        $data = $request->validate([
            'template_type' => 'required|string|max:255',
            'content' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Strip HTML tags and check if there's actual text
                    $stripped = strip_tags($value);
                    if (trim($stripped) === '') {
                        $fail('The template content cannot be empty.');
                    }
                },
            ],
        ]);

        // Save or update based on template_name match
        $template = Template::where('template_name', $data['template_type'])->first();

        if ($template) {
            $template->update(['content' => $data['content']]);

            activity()
                ->performedOn($template)
                ->causedBy(auth()->user())
                ->inLog("Edited {$template->template_type} template") 
                ->event('edited')
                ->withProperties([
                    'user_name' => $user->name,
                    'template_name' => $template->template_name,
                ])
                ->log("Edited {$template->template_type} template");

            return redirect()
            ->back()
            ->with('status', 'Template updated successfully!');
        }else{
            return redirect()
            ->back()
            ->withErrors('Template not found!');
        }       
    }
}
