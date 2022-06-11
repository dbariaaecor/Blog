@extends('layouts.layout')


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-8">
                @foreach (auth()->user()->notifications as $notification )
                @if (array_key_exists('title', $notification->data))
                <div class="alert alert-primary" role="alert">
                     Your Post <strong><a href="">{{$notification->data['title']}}</a></strong> is Approved by <strong><a href="">{{$notification->data['user']}}</a></strong>
                </div>
                @endif

                @if (array_key_exists('comment', $notification->data))
                <div class="alert alert-primary" role="alert">
                    Your blog "{{$notification->data['post']['title']}}" is Rejected by {{$notification->data['from']}}, bcz " {{$notification->data['comment']}} ".

                    @if($notification->data['post']['isApprove']==0)
                        <a href="{{route('edit',$notification->data['post']['slug'])}}" class="btn btn-warning">Update Post</a>
                        <a href="{{route('delete',$notification->data['post']['slug'])}}" class="btn btn-danger">Delete Post</a>
                    @else
                        {{ "read" }}
                    @endif
               </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
