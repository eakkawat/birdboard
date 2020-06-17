@extends('layouts.app')

@section('header')
    <style>
        textarea:focus {
            border-color: white;
        }

        .description {
            margin-top: 2.5rem;
        }

        textarea.notes, input.task {
            outline: none;
        }

        .member {
            display: inline-block;
        }

    </style>
@endsection

@section('content')

     <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between items-end w-full">
            <p class="text-default">
                <a href="{{ route('projects.index') }}" class="no-underline">My Projects</a> / {{ $project->title }}
            </p>
            <div class="flex items-center">

                @foreach ($project->members as $member)
                    <img src="{{ gravatar_url($member->email) }}" alt="{{ $member->name }}'s avatar'" class="member mr-2 rounded-full w-8">
                @endforeach

                <img src="{{ gravatar_url($project->owner->email) }}" alt="{{ $project->owner->name }}'s avatar'" class="member mr-2 rounded-full w-8">
                
                <a  href="{{ route('projects.edit', $project) }}" class="btn ml-6">Edit Project</a>

            </div>
        </div>
        
    </header>

    <main>
        <div class="lg:flex">
            <div class="lg:w-3/4 lg:pr-3 mb-8">
                <div class="mb-8">
                    <h2 class="text-lg text-gray-lighter font-normal mb-3">Tasks</h2>

                        @foreach ($project->tasks as $task)

                            <div class="card mb-3">
                                    <form action="{{ route('project.tasks.update',[$project,$task]) }}" method="POST">

                                    @method('PATCH')
                                    @csrf
                                    
                                    <div class="flex items-center ">
                                        <input type="text" name="body" value="{{ $task->body }}" class="text-default bg-card w-full task {{ $task->completed ? 'text-gray-lighter':'' }}">
                                        <input type="checkbox" name="completed" onchange="this.form.submit()" {{ $task->completed ? 'checked':'' }}>
                                    </div>
                                    
                                </form>
                            </div>
                                                   
                        @endforeach

                        <div class="card mb-3">
                            <form action="{{ route('project.tasks.store', $project->id) }}" method="POST">                                
                                @csrf
                                <input type="text" class="text-default bg-card w-full task" name="body" placeholder="Add a new task...">
                            </form>
                        </div>
                    
                </div>
                <div>
                    <h2 class="text-lg text-default font-normal mb-3">General Notes</h2>
                  
                        <form action="{{ route('projects.update',$project) }}" method="POST">
                            @csrf
                            @method('PATCH')
                           <div class="card mb-4">
                                <textarea cols="30" rows="10" name="notes" class="text-default bg-card w-full notes" placeholder="Anything special that you want to make a note of ?">{{ $project->notes }}</textarea>
                           </div>
                            <button class="btn" type="submit">Save</button>
                        </form>
                        @include('projects._errors')
                 
                </div>
            </div>

            <div class="lg:w-1/4 lg:pl-3">
                <div class="description">
                    @include('projects._card')

                    @include('projects.activities._activities')
                    
                    @can('manage', $project)
                        @include('projects._invite')
                    @endcan
                    
                </div>
            </div>
    
        </div>

    </main>

        
  
    
@endsection