@extends('layouts.app')

@section('content')
    <div class="lg:w-1/2 lg:mx-auto bg-white p-6 md:py-12 md:px-16 rounded shadow mt-8">

        <h1 class="text-2xl font-normal mb-10 text-center">
            Lets start something new
        </h1>
        
        <form 
        action="/projects" 
        method="POST"
        >
        
        @include('projects.form', [
            'project' => new App\Project,
            'buttonText' => 'Create Project'
        ])
        
        </form>
    </div>
    {{-- <form 
        action="/projects" 
        method="POST"
        class="lg:w-1/2 lg:mx-auto bg-white p-6 md:py-12 md:px-16 rounded shadow"
    >

        @csrf
   
        <h1 class="text-2xl font-normal mb-10 text-center">
            Let's start something new
        </h1>

        <div class="field mb-6">
            <label class="label text-sm mb-2 block" for="title">Title</label>
            
            <div class="control">
                <input 
                    type="text" 
                    class="input bg-transparent border border-grey-300 rounded p-2 text-xs w-full" 
                    name="title" 
                    placeholder="My next awesome project">
            </div>
        </div>

        <div class="field">
            <label class="label text-sm mb-2 block" for="description">Description</label>
            <div class="control">
                <input 
                    type="text" 
                    class="input bg-transparent border border-grey-300 rounded p-2 text-xs w-full mb-4 " 
                    name="description" 
                    placeholder="Description here">
            </div>
        </div>

        <div class="field">
            <div class="control">
                <button 
                    type="submit" 
                    class="bg-blue-400 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-full">
                    Create a Project
                </button>
                <a href="/projects">Cancel</a>
            </div>
        </div>  
    </form> --}}

@endsection 