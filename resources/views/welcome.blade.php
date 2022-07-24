@extends('layouts.layout')
@section('content')
<div class="container">
    <div class="row ">
        <div class="col-md-12">
            <h1 class="text text-center">Blog Post</h1>
            <hr class="col-md-11">
        </div>
        <div class="col-md-8">
            @if ($posts!=null)
            @foreach ($posts as $post )
            <div class="card  col-md-12 m-2">
                <img src="{{$post->getMedia('cover_image')[0]->getUrl()}}" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title"><a href="{{route('userblogs',[$post->user->username,$post->slug])}}">{{$post->title}}</a></h5>
                    <p class="card-text"> {{$post->preview_content}}</p>
                    <hr>
                    <p class="card-text">Last updated  {{$post->updated_at->toDateString()}} | {{$post->updated_at->toTimeString()}} ({{$post->updated_at->diffForHumans()}})</p>
                </div>
            </div>
            @endforeach
            <div class="row justify-content-center">
                <div class="col-md-3">
                    {!! $posts->links() !!}
                </div>
            </div>
        </div>
            <div class="col-md-3">
                <div class="card" style="width: 100%;">
                <div class="card-body">
                    <h5 class="card-title">Recent Blog's</h5>
                @foreach ($topfiveposts as $post )
                    <div class="card m-2" style="width: 100%;">
                        <img class="card-img-top" src="{{$post->getMedia('cover_image')[0]->getUrl()}}" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title"><a href="{{route('userblogs',[$post->user->username,$post->slug])}}">{{$post->title}}</a></h5>
                        </div>
                    </div>
                @endforeach
                </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
