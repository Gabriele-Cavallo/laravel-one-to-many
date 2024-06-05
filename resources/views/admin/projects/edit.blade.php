@extends('layouts.admin')

@section('content')
<h2>MODIFICA IL PROGETTO</h2>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.projects.update', $project->slug)}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    {{-- Project name input --}}
    <div class="mb-3">
      <label for="name" class="form-label">Project name</label>
      <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp" value="{{ old('name', $project->name)}}">
    </div>
    @error('name')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    {{-- /Project name input --}}

    {{-- Image input --}}
    <div class="mb-3">
        <label for="cover_image" class="form-label">Upload image</label>
        <input class="form-control" type="file" id="cover_image" name="cover_image">
        @if ($project->cover_image)
            <img class="mt-3" src="{{ asset('storage/' . $project->cover_image) }}" alt="{{ $project->name }}">
        @else
            <p>No image found</p>
        @endif
    </div>
    {{-- Image input --}}

    {{-- Client name input --}}
    <div class="mb-3">
      <label for="client_name" name="client_name" class="form-label">Client name</label>
      <input type="text" class="form-control" id="client_name" name="client_name" aria-describedby="emailHelp" value="{{ old('client_name',$project->client_name)}}">
    </div>
    @error('client_name')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    {{-- /Client name input --}}

    {{-- Summary text area --}}
    <div class="mb-3">
      <label for="summary" name="summary" class="form-label">Project summary</label>
      <textarea type="text" rows="10" class="form-control" id="summary" name="summary" aria-describedby="emailHelp">{{ old('summary', $project->summary)}}</textarea>
    </div>
    @error('summary')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    {{-- /Summary text area --}}
    
    {{-- Submit-return button --}}
    <button type="submit" class="btn btn-dark">Edit</button>
    <button class="btn btn-dark">
        <a class="nav-link text-white" href="{{ route('admin.projects.index') }}">
            <i class="fa-solid fa-arrow-left fa-lg fa-fw"></i> Return
        </a>
    </button>
    {{-- /Submit-return button --}}
</form>
    
@endsection