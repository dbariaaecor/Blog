@extends('layouts.layout')

@section('title','Post')

@section('content')
    @section('pageheader',$post->title)
    <div class="container">
        <div class="row justify-content-center">
            <span>{{session('message')}}</span>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <span>Blog Post</span>
                    </div>
                    <div class="card-body">
                      <h5 class="card-title"> {{$post->title}}</h5>
                      <div class="post_container">
                        {!! html_entity_decode($post->text_content) !!}
                    </div>
                     <span>Want To Update?</span>
                      <a href="{{route('editpost',$post->slug)}}" class="btn btn-primary">Update</a>
                    </div>
                  </div>
            </div>
        </div>
    </div>
@endsection

@section('links')
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<Script>
    $(document).ready(function(){
        $('.post_container').find('img').css('max-width','90%');
    });
</Script>
@endsection
