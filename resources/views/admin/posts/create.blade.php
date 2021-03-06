@extends('layouts.app')
@section('content')
  <div class="container">
    <div class="row">
      <h2>{{Auth::user()->name}}</h2>
      <form enctype="multipart/form-data" action="{{route('admin.posts.store')}}" method="post">
        @csrf 
        @method('POST')
        <div class="form-group">
          <label for="title">Title</label>
          <input class="form-control" type="text" name="title">
        </div>

        <div class="form-group">
          <label for="body">Body</label>
          <textarea class="form-control" name="body" id="body" cols="30" rows="10">

          </textarea>
        </div>
            <div class="form-group"> <label for="tags">Tags</label> @foreach ($tags as $tag) <div> <span>{{$tag->name}}</span> <input type="checkbox" name="tags[]" value="{{$tag->id}}"> </div> @endforeach 
          </div>
          <input type="file" name="img" value="">                        
        <button class="btn btn-success" type="submit">Salva</button>
      </form>
    </div>
  </div>

  
    
@endsection
