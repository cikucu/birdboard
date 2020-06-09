<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectRequest;
use App\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->accessibleProjects();

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $this->authorize('update', $project);
        // abort_if(auth()->id() != $project->owner_id,403);

        // if(auth()->user()->isNot($project->owner)){
        //     abort(403);
        // }

        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {
        
        $attributes = request()->validate([
            'title' => 'required', 
            'description' => 'required',
            'notes' => 'min:3'
        ]);
        
        $attributes = $this->validateRequest();

        $project = auth()->user()->projects()->create($attributes);

        if(request()->wantsJson()) {
            return ['message' => $project->path()];
        }

        return redirect($project->path());
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request ,Project $project)
    {
        // $project->update($request->validated());
        $request->persist();

        return redirect($request->project()->path());
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);

        $project->delete();

        return redirect('/projects');
    }

    public function validateRequest()
    {
        return request()->validate([
                'title' => 'sometimes|required', 
                'description' => 'sometimes|required',
                'notes' => 'nullable'
        ]);
    }

    
}
