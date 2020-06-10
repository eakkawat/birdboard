
    <div class="field mb-6">
        <label class="label text-sm mb-2 block" for="title">Title:</label>
        <div class="control">
            <input type="text" name="title" value="{{ $project->title }}" required placeholder="My next awesome project" class="input bg-transparent border border-gray rounded p-2 text-xs w-full">
        </div>
    </div>

    <div class="field mb-6">
        <label class="label text-sm mb-2 block" for="description">Description:</label>
        <div class="control">
            <textarea name="description" cols="30" rows="10" required placeholder="I should start learing piano." class="textarea bg-transparent border border-gray rounded p-2 text-xs w-full">{{ $project->description }}</textarea>  
        </div>
    </div>

    <div class="field">
        <button type="submit" class="btn">{{ $submit }}</button>
        <a href="{{$project->path()}}" class="ml-2">Cancel</a>
    </div>

    @include('projects._errors')