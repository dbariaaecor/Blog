@extends('layouts.layout')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-8">
                @foreach (auth()->user()->notifications as $notification )
                @if (array_key_exists('title', $notification->data))
                <div class="alert alert-primary" role="alert">
                     Your Post <strong><a href="{{route('blog.viewblog',[$notification->data['slug']])}}">{{$notification->data['title']}}</a></strong > is <span style="color:green;"><strong>Approved</strong></span> by <strong>{{$notification->data['user']}}</strong>
                </div>
                @endif
                @if (array_key_exists('comment', $notification->data))
                <div class="alert alert-primary" role="alert">
                    Your blog <a href="{{route('blog.edit',[$notification->data['post']['slug']])}}"><strong>{{$notification->data['post']['title']}}</strong></a> is <span style="color: red;"><strong>Rejected</strong></span> by <strong>{{$notification->data['from']}}</strong>.
                    <h6><span style="color: red;"><strong>Reason: <span style="color:blue;">{{$notification->data['comment']}}</span></strong></span></h6>
               </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection

