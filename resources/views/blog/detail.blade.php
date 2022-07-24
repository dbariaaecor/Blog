@extends('layouts.layout')
@section('title','Detail Blog')
@section('page-css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> --}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row ">
            <div class="col-md-8 ">
                <div class="col-md-12">
                    <h2>{{$userslug." blog"}}</h2>
                    <hr>
                    @if ($post!=null)
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                          <h5 class="card-title">{{$post->title}}</h5>
                           <div class="m-2 border">
                               <img  class="d-block w-100" src="{{$post->getMedia('cover_image')[0]->getUrl()}}" alt="">
                           </div>
                          <p class="card-text">{!!  html_entity_decode($post->text_content) !!}</p>
                          <hr>
                           <h5>Alternative Images</h5>
                           <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @php
                                    $i=0;
                                @endphp
                                @foreach ($post->getMedia() as $media)
                                   @if ($i==0)
                                   <div  class="carousel-item active">
                                        <img class="d-block w-100" src="{{$media->getUrl()}}">
                                    </div>
                                    @else
                                    <div  class="carousel-item">
                                        <img class="d-block w-100" src="{{$media->getUrl()}}">
                                    </div>
                                   @endif
                                   @php
                                    $i=1;
                                @endphp
                                @endforeach
                            </div>
                              <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                              </button>
                              <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                              </button>
                          </div>
                        </div>
                        Post Created at :
                        @if($post!=null)
                             {{" ".$post->created_at->toDateString()}} | {{$post->created_at->toTimeString()}}
                        @endif
                        @guest
                        @else
                            @if (Auth::user()->username == $post->user->username)
                            <div class="card-fotter">
                                <a href="{{route('blog.edit',$post->slug)}}" class="btn btn-warning">Update</a>
                                <a href="{{route('blog.delete',$post->slug)}}" class="btn btn-danger">Delete</a>
                            </div>
                            @endif
                        @endguest
                    </div>
                    @endif

                </div>
            </div>

            <div class="col-md-4" >
                <div class="clo-md-4 " >
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title"><strong>Tags</strong></h5>
                            @foreach ($tags as $tag )
                                <a href="{{route('getposts',$tag->slug)}}" class="btn btn-sm btn-secondary m-1">{{$tag->slug}}</a>
                            @endforeach
                        </div>
                    </div>
                    @if ($post!=null)
                    <div class="card" style="margin-top:10px;">
                        <div class="card-body">
                            <h5 class="card-title"><strong>About Author</strong></h5>
                            <h5 class="card-text">Name: {{" ".$post->user->username}}</h5>
                            @if (json_decode($post->user->experiance,true)!=null)
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                  <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                      Technologies
                                    </button>
                                  </h5>
                                </div>
                                <div id="collapseOne" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordion">
                                  <div class="card-body">
                                    @foreach (json_decode($post->user->experiance,true) as $tech)
                                    <div class="card" style="margin-top:5px;">
                                        <div class="card-body">
                                            <h5 class="card-title">Technology:{{$tech['technology']}}</h5>
                                            <h5 class="card-title ">Level:{{$tech['level']}}</h5>
                                            <h5 class="card-title">Experiance:{{$tech['expreience'] > 1 ? $tech['expreience']." years": $tech['expreience']." year"}}</h5>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        </div>
                    </div>
                    @endif
                    <div class="card" style="width: 100%; margin-top:10px;">
                        <div class="card-body">
                          <h5 class="card-title"><strong>Recent Blog's From Author</strong></h5>
                            @foreach ($newposts as $newpost )
                                <div class="card m-1" >
                                    <div class="card-body " style="width: 100%; height:10%;">
                                        <h5 class="card-title">
                                            <a href="{{route('userblogs',[$newpost->user->username,$newpost->slug])}}">{{$newpost->slug}}</a>
                                        </h5>
                                        @foreach ($newpost->getMedia('cover_image') as $media )
                                            <img src="{{$media->getUrl()}}" style="height:10%;" class="img-thumbnail">
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-js')
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
@endsection
