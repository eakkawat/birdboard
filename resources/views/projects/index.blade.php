@extends('layouts.app')

@section('content')

    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between items-end w-full">
            <span class="text-gray-lighter">My projects</span>
            <a  href="#" @click.prevent = "$modal.show('new-project')" class="btn">Add Project</a>
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

    <new-project-modal></new-project-modal>

@endsection