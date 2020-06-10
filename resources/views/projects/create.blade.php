@extends('layouts.app')

@section('content')

    <div class="lg:w-1/2 lg:mx-auto bg-white p-6 md:py-12 md:px-16 rounded shadow">
        <h1 class="text-2xl font-nolmal mb-10 text-center">Let's start something new</h1>

        <form action="{{ route('projects.store') }}" method="post" >
            @csrf

            @include('projects._form', [
                'project' => new App\Project,
                'submit' => 'Add Project'
                ])

        </form>
        
        
    </div>

   
    
@endsection