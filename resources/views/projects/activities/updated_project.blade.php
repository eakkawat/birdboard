
@if (count($activity->changes['after']) == 1)
    {{ auth()->id() === $activity->user->id ? 'You': $activity->user->name }} updated the {{ key($activity->changes['after']) }} of the project
@else
    {{ auth()->id() === $activity->user->id ? 'You': $activity->user->name }} updated the project
@endif