@extends('layouts.layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h2>{{$userslug." blog"}}</h2>
                <hr>
                @if ($post!=null)
                <div class="card" style="width: 50rem;">
                    <div class="card-body">
                      <h5 class="card-title">{{$post->title}}</h5>
                      <p class="card-text">{!!  html_entity_decode($post->text_content) !!}</p>

                      @foreach ($post->getMedia() as $media)
                        <img src="{{$media->getUrl()}}"  class="img-thumbnail">
                      @endforeach
                    </div>
                    @if (Auth::user()->username == $post->user->username)
                    <div class="card-fotter">
                        <a href="{{route('edit',$post->slug)}}" class="btn btn-warning">Update</a>
                        <a href="{{route('delete',$post->slug)}}" class="btn btn-danger">Delete</a>
                    </div>
                    @endif
                </div>
                @endif

                @if ($oldpost!=null)
                <div class="card" style="width: 50rem;">
                    <div class="card-body">
                      <h5 class="card-title">{{$oldpost->title}}(will update soon)</h5>
                      <p class="card-text">{!!  html_entity_decode($oldpost->text_content) !!}</p>
                        @foreach ($oldpost->getMedia("temp_post_images") as $media)
                            <img src="{{$media->getUrl()}}"  class="img-thumbnail">
                        @endforeach
                    </div>

                    @if (Auth::user()->username == $oldpost->user->username)
                    <div class="card-fotter">
                        <a href="{{route('edit',$oldpost->slug)}}" class="btn btn-warning">Update</a>
                        <a href="{{route('delete',$oldpost->slug)}}" class="btn btn-danger">Delete</a>
                    </div>
                    @endif
                </div>
                @endif

            </div>
        </div>
    </div>
@endsection
