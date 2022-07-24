@extends('layouts.layout')
@section('content')
@section('title','Blogs')
<div class="container-fluid">
    <div class="row ">
        <a href="{{URL::previous()}}"  class="col-md-1 btn btn-secondary ">back</a>
        <h1 class="offset-4">Blog Post</h1>
        <hr class="col-md-10">
        @if (count($posts) > 0)
        @php
            $i=0;
        @endphp
        @foreach ($posts as $post )
            <div class="card mb-3 col-md-8 offset-1">
            <img src="{{$post->getMedia('cover_image')[0]->getUrl()}}" class="card-img-top" height="200px">
            <div class="card-body">
              <h5 class="card-title"><a href="{{route('userblogs',[$post->user->username,$post->slug])}}">{{$post->title}}</a></h5>
              <p class="card-text">{!!$text[$i]!!}</p>
              @foreach ($post->tags as $tag)
                <button class="btn btn-secondary">{{$tag->name}}</button>
              @endforeach
              @php
                  $i++;
              @endphp
              <hr>
              <p class="card-text">Last updated {{$post->updated_at->toTimeString()}}</p>
            </div>
          </div>

        @endforeach


        @if (count($oldposts) > 0)
                @php
                 $i=0;
                @endphp
            @foreach ($oldposts as $oldpost)

            <div class="card mb-3 col-md-8 offset-1">
                <img src="{{$oldpost->getMedia('temp_cover_image')[0]->getUrl()}}" class="card-img-top" height="200px">
                <div class="card-body">

                  <h5 class="card-title"><a href="{{route('userblogs',[$oldpost->user->username,$oldpost->slug])}}">{{$oldpost->title}} (will update soon)</a></h5>

                  <p class="card-text">{!!$oldtext[$i]!!}</p>
                  @foreach (json_decode($oldpost->tags,true) as $tag)
                     <button class="btn btn-sm btn-secondary">{{$tag['name']}}</button>
                @endforeach
                  @php
                      $i++;
                  @endphp
                  <hr>
                  <p class="card-text">Last updated {{$oldpost->updated_at->toTimeString()}}</p>
                </div>
              </div>
            @endforeach
        @endif

        @else
            <h2 class="offset-4">No Posts</h2>
            <h2 class="offset-4">Available</h2>
        @endif
    </div>
</div>
@endsection
