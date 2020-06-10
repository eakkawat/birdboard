@extends('layouts.app')

@section('content')

<div class="lg:w-1/2 lg:mx-auto bg-white p-6 md:py-12 md:px-16 rounded shadow">
    <h1 class="text-2xl font-nolmal mb-10 text-center">Edit Your Project</h1>

    <form action="{{ route('projects.update', $project) }}" method="post" >
        @csrf
        @method('PATCH')

        @include('projects._form',[
            'submit' => 'Update Project'
        ])

    </form>
    
    
</div>
    
@endsection