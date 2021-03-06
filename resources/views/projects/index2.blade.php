@extends('layouts.app')

@section('content')
    <div class="flex items-center mb-3">
        <a href="/projects/create">New Project</a>
    </div>

    <div class="flex">
        @forelse ($projects as $project)
            <div class="bg-white">
                <h3>{{$project->title}}</h3>
                <h3>{{$project->description}}</h3>
            </div>
        @empty
            <div>No projects yet.</div>
        @endforelse
    </div>

@endsection