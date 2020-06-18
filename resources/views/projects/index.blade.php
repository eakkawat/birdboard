@extends('layouts.app')

@section('content')

    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between items-end w-full">
            <span class="text-gray-lighter">My projects</span>
            <a  href="{{ route('projects.create') }}" class="btn">Add Project</a>
        </div>
        
    </header>

    <main class="flex flex-wrap -mx-3">
        @forelse ($projects as $project)
            <div class="w-1/3 px-3 pb-6">
                @include('projects._card')
            </div>
        @empty
            <div>No Project yet.</div>
        @endforelse
    </main>

    <modal name="hello-world" classes="p-10 bg-card rounded-lg" height="auto">
        <h1 class="font-normal mb-16 text-center text-2xl">Let's start something new.</h1>
        <div class="flex">
            <div class="flex-1 mr-4">
                <div class="mb-4">
                    <label for="title" class="text-sm block mb-2">Title</label>
                    <input type="text" id="title" placeholder="My awesome nexr project" class="border border-gray rounded py-2 px-2 text-xs block w-full ">
                </div>
                <div class="mb-4">
                    <label for="description" class="text-sm block mb-2">Description</label>
                    <textarea class="w-full rounded border border-gray text-xs block py-2 px-2" placeholder="I should start learning piano." id="description" rows="7"></textarea>
                </div>
            </div>
            <div class="flex-1 ml-4">
                <div class="mb-4">
                    <label for="task" class="text-sm block mb-2">Need some tasks?</label>
                    <input type="text" id="task" placeholder="task 1" class="border border-gray rounded py-2 px-2 text-xs block w-full">
                </div>
                <div class="mb-4">
                    <button class="inline-flex items-center">
                        <svg style="height: 20px; stroke: #000; opacity: .5;" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" fill="none" stroke-width="7.5"></circle>
                            <line x1="32.5" y1="50" x2="67.5" y2="50" stroke-width="5"></line>
                            <line x1="50" y1="32.5" x2="50" y2="67.5" stroke-width="5"></line>
                          </svg>
                          <span class="ml-2 text-xs">Add new task field</span>
                    </button>
                </div>
            </div>
        </div>
        <footer class="mt-4 flex justify-end">

            <button class="btn mr-2 is-outlined">Cancel</button>
            <button class="btn">Create Project</button>
            
        </footer>
    </modal>

    <a href="#" @click.prevent = "$modal.show('hello-world')">Add task</a>
@endsection