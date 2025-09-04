@extends('frontend.layouts.app')

@section('title', 'Upload Flight Crew Document' )

@section('content')

    <section class="content">
        <div class="container-fluid">
        <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Upload New PDF File</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.pilotLibrary.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                       <div class="form-group">
                           <label>Year</label>
                           <select name="year" class="form-control" required>
                               <option selected disabled>Select a year</option>
                               @for($i = date('Y'); $i >= 2021; $i--)
                                   <option>{{ $i }}</option>
                                   @endfor
                           </select>
                       </div>

                        @foreach($content_categories as $category)
                        <div class="form-group mt-0">
                            <label class="mt-4">{{ $category->name }}</label>
                            @foreach($category->childrenCategory as $child_category)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="category[]" value="{{ $child_category->id }}" id="{{ \Illuminate\Support\Str::slug($child_category->name, '_') }}">
                                    <label class="form-check-label" for="{{ \Illuminate\Support\Str::slug($child_category->name, '_') }}">
                                        {{ $child_category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @endforeach

                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="file">File</label>
                            <input type="file" class="form-control" id="file" accept=".pdf" name="file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
        </div>
    </section>

@endsection
