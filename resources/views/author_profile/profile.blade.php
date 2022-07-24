@extends('layouts.layout')
@section('title','profile')

@section('content')
    <div class="container">
        <div class="row ">
                 <div class="col-md-10">
                    @if (session()->has('message'))
                        <span class="alert alert-success">{{session()->get('message')}}</span>
                     @endif
                    <h2 class="text text-center">Profile</h2>
                    <div class="row">
                        <div class="col-md-12 m-2">
                            <div class="card text-center">
                                <div class="card-header">
                                   <h3> Personal Detail</h3>
                                </div>
                                <div class="card-body">
                                <h4 class="card-title">{{$user->username}}</h5>
                                @if ($user->Auth_Description!=null)
                                    <p class="card-text my-2">{{$user->Auth_Description}}</p>
                                @endif
                                @if ($user->phone!=null)
                                    <h5 class="mt-3"><span>Contect Info</span></h5>
                                    <h5><span>Phone:{{Auth::user()->phone}}</span></h5>
                                @endif
                                @if (Auth::user()->username == $user->username)
                                    <a href="{{route('user.profileedit',Auth::user()->username)}}" class="btn btn-primary">Edit</a>
                                @endif
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 m-2">
                            <div class="card text-center">
                                <div class="card-header">
                                 <h3> Professional Details</h3>
                                </div>
                                <div class="card-body">
                                    @if ($user->experiance!=null)
                                    <div class="row justify-content-center">
                                        @foreach (json_decode($user->experiance,true) as $tech )
                                            <div class="card col-md-12 mx-2 my-2" style="width: 100%;">
                                                <h2 class="card-title">Technology:{{$tech['technology']}}</h2>
                                                <div class="card-body">
                                                    <h5 class="card-title border border-dark">{{$tech['des']}}</h5>
                                                    <h5 class="card-title "><strong>Level:{{$tech['level']}}</strong></h5>
                                                    <h5 class="card-title"><strong>Experiance:{{$tech['expreience'] > 1 ? $tech['expreience']." years": $tech['expreience']." year"}}</strong></h5>
                                                </div>
                                            </div>
                                        @endforeach
                                    <div>
                                @endif

                                @if(count($user->getMedia('cv')->toArray())==0)
                                        <div></div>
                                @else
                                <img src="{{asset('img/file.jpg')}}" width="100px" height="100px" >

                                <a href="{{route('download',$user->getMedia('cv')[0]->uuid)}}">CV</a>
                                @endif
                           </div>
                           @if (Auth::user()->username == $user->username)
                            <a href="{{route('user.professionalprofiledit',Auth::user()->username)}}" style="width: 65px; margin:auto;" class="btn btn-primary">Edit</a>
                            @endif
                        </div>
                    </div>
                 <div>
        </div>
    </div>
@endsection
