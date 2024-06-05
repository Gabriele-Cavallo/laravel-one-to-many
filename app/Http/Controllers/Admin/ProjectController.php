<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|min:1|max:100|unique:projects,name',
                'client_name' => 'required|min:1|max:100',
                'summary' => 'nullable|min:10',
                'cover_image' => 'nullable|image|max:512'
            ],
            [
                'name.required' => 'Il nome del progetto è obbligatorio',
                'name.min' => 'Il nome deve contenere almeno 1 carattere',
                'name.max' => 'Il nome può contenere al massimo 100 caratteri',
                'name.unique' => 'Il nome del progetto è già stato utilizzato',
                'client_name.required' => 'Il nome del cliente è obbligatorio',
                'client_name.min' => 'Il nome del cliente deve contenere almeno 1 carattere',
                'client_name.max' => 'Il nome del cliente può contenere al massimo 100 caratteri',
                'summary.min' => 'La descrizione deve contenere almeno 10 caratteri o essere vuota',
                'cover_image.image' => 'Il file caricato non è un\'immagine',
                'cover_image.max' => 'Il file caricato è troppo grande, i file possono essere al massimo 512kb.'
            ]
        );
        $form = $request->all();
        $form['slug'] = Str::slug($form['name'], '-');
        
        if ($request->hasFile('cover_image')) {
            $img_path = Storage::disk('public')->put('projects_images', $form['cover_image']);
            $form['cover_image'] = $img_path;
        }

        $newProject = new Project();
        $newProject->fill($form);
        // $newProject->slug = Str::slug($newProject->name, '-');
        $newProject->save();

        // session()->flash('message', $newProject->name . 'successfully created.');
        
        return redirect()->route('admin.projects.show', $newProject->slug)->with('message', $newProject->name . ' successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'min:1',
                    'max:100',
                    Rule::unique('projects')->ignore($project),
                ],
                'client_name' => 'required|min:1|max:100',
                'summary' => 'nullable|min:10',
                'cover_image' => 'nullable|image|max:512'
            ],
            [
                'name.required' => 'Il nome del progetto è obbligatorio',
                'name.min' => 'Il nome deve contenere almeno 1 carattere',
                'name.max' => 'Il nome può contenere al massimo 100 caratteri',
                'name.unique' => 'Il nome del progetto è già stato utilizzato',
                'client_name.required' => 'Il nome del cliente è obbligatorio',
                'client_name.min' => 'Il nome del cliente deve contenere almeno 1 carattere',
                'client_name.max' => 'Il nome del cliente può contenere al massimo 100 caratteri',
                'summary.min' => 'La descrizione deve contenere almeno 10 caratteri o essere vuota',
                'cover_image.image' => 'Il file caricato non è un\'immagine',
                'cover_image.max' => 'Il file caricato è troppo grande, i file possono essere al massimo 512kb.'
            ]
        );

        $form = $request->all();
        if ($request->hasFile('cover_image')) {
            if ($project->cover_image) {
                Storage::delete($project->cover_image);
            }
            $img_path = Storage::disk('public')->put('projects_images', $form['cover_image']);
            $form['cover_image'] = $img_path;
        }
        $project->slug = Str::slug($form['name'], '-');
        $project->update($form);

        return redirect()->route('admin.projects.show', $project->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        if ($project->cover_image) {
            Storage::delete($project->cover_image);
        }
        $project->delete();

        return redirect()->route('admin.projects.index')->with('message', $project->name . ' successfully deleted.');
    }
}
