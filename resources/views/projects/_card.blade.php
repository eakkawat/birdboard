<div class="card flex flex-col" style="min-height: 200px;">
    <h3 class="font-normal text-xl py-4 -ml-5 border-l-4 border-blue-lighter pl-4 mb-3 text-default">
        <a href="{{ $project->path() }}">{{ $project->title }}</a>
    </h3>
    <div class="text-default mb-4 flex-1">{{ str_limit($project->description, 50) }}</div>
    @can('manage', $project)

        <footer>
            <form class="text-right" method="POST" action="{{ route('projects.destroy', $project) }}">

                @csrf
                @method('DELETE')

                <button type="submit" class="text-xs text-default">Delete</button>
                
            </form>
        </footer>
        
    @endcan
</div>