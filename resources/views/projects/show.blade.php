@extends('layouts.app')

@section('content')
    <header class="flex items-center mb-3 py-4"> 
        <div class="flex justify-between items-end w-full">
            <p class="text-gray-600 text-sm font-normal">
                <a href="/projects" > My Projects </a>/ {{$project->title}} 
            </p>
            <div class="flex items-center">

                @foreach ($project->members as $member)
                    <img src="{{ gravatar_url($member->email) }}" 
                     alt="{{$member->name}}'s avatar'" 
                     class="rounded-full w-8 mr-2">
                    
                @endforeach
                    <img 
                     src="{{gravatar_url($project->owner->email)}}" 
                     alt="{{$project->owner->name}}'s avatar'" 
                     class="rounded-full w-8 mr-2">
                
                <a href="{{$project->path(). '/edit'}}" class="bg-blue-400 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-full ml-4">Edit Project</a>
            </div>
        </div>
    </header>

    <main>
        <div class="lg: flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">

                    <h2 class="text-lg text-gray-600 font-normal mb-3">Tasks</h2>
                    {{-- Tasks  --}}
                    @foreach ($project->tasks as $task)
                        <div class="bg-white mr-4 p-5 rounded-lg shadow mb-3">
                            {{-- //masih error --}}
                        <form method="POST" action="{{ $task->path() }}">
                            @method('PATCH')
                            @csrf

                                <div class="flex">
                                <input type="text" name="body" value="{{$task->body}}" class="w-full {{$task->completed ? 'text-gray-500' : ''}}">
                                    <input name="completed" type="checkbox" onchange="this.form.submit()" {{$task->completed ? 'checked' : ''}}>
                                </div>
                        </form>
                        </div>
                        
                    @endforeach
                        <div class="bg-white mr-4 p-5 rounded-lg shadow mb-3">
                            <form action="{{$project->path(). '/tasks'}}" method="POST">
                                @csrf
                                <input type="text" placeholder="Add a New Task..." class="w-full" name="body">    
                            </form>
                        </div>

                </div>

                <div>

                    <h2 class="text-lg text-gray-600 font-normal" >General Notes</h2>
                    {{-- General Notes  --}}
                    <form method="POST" action="{{ $project->path() }}">
                        @csrf
                        @method('PATCH')
                        
                        <textarea class="bg-white mr-4 p-5 rounded-lg shadow w-full mb-4" 
                                style="min-height: 200px" 
                                placeholder="Anything special that you want to make a note of?" 
                                name="notes">
                            {{$project->notes}}
                        </textarea>
                        <button type="submit" class="bg-blue-400 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-full"> 
                            Save 
                        </button>
                    </form>
                    @include('errors')
                </div>
            </div>

            <div class="lg:w-1/4 px-3 lg:py-8">
                    @include('projects.card')
                    @include('projects.activity.card')

                    @can('manage', $project)
                        
                        @include('projects.invite')
                        
                    @endcan
                    
            </div>
        </div>
    </main>



    
@endsection