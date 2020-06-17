<div class="card mt-3 text-default">

    <ul class="text-xs">
        @foreach ($project->activities as $activity)

            <li class="{{ $loop->last?'':'mb-1' }}">
                @php
                    $log_view = str_replace(' ','_',$activity->description);   
                @endphp
                @include('projects.activities.'.$log_view) 
                <span class="text-default ml-2">{{$activity->created_at->diffForHumans()}}</span>
            
            </li>

        @endforeach
    </ul>
    
</div>