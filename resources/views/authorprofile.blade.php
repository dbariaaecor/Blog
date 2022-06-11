@extends('layouts.layout')
@section('title','profile')

@section('content')
    <div class="container">
        <div class="row">
             <div class="col-md-8">
                 @if (session()->has('message'))
                    <div><span style="color: green;">{{session()->get('message')}}</span></div>
                 @endif
                <div class="card text-center">
                    <div class="card-header">
                      Profile
                    </div>
                    <div class="card-body">
                      <h4 class="card-title">{{$post->username}}</h5>
                      @if ($post->Auth_Description!=null)
                        <p class="card-text">{{$post->Auth_Description}}</p>
                      @endif
                      @if ($post->experiance!=null)
                      <div class="row justify-content-center">
                        @foreach (json_decode($post->experiance,true) as $key=>$tech )
                        <div class="card col-md-2 mx-2 my-2" style="width: 18rem;">
                         <div class="card-body">
                           <h5 class="card-title">Technology:{{$tech['technology']}}</h5>
                           <h5 class="card-title ">Level:{{$tech['level']}}</h5>
                           <h5 class="card-title">Experiance:{{$tech['expreience'] > 1 ? $tech['expreience']." years": $tech['expreience']." year"}}</h5>
                         </div>
                       </div>
                        @endforeach
                      </div>

                      @endif

                      @if ($post->phone!=null)
                        <h5 class="mt-3"><span>Contect Info</span></h5>
                        <h5><span>Phone:{{Auth::user()->phone}}</span></h5>
                      @endif
                      @if (Auth::user()->username == $post->username)
                        <a href="{{route('profileedit',Auth::user()->username)}}" class="btn btn-primary">Edit</a>
                      @endif
                    </div>
                  </div>
             </div>
        </div>
    </div>

@endsection
