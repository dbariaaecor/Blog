@extends('layouts.layout')

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col">
                <div class="col-md-8">
                    <a href="{{URL::previous()}}"  class="col-md-1 m-1 btn btn-secondary ">back</a>

                    @if($post->temppost!=null)
                    <div class="card" style="width: 50rem;">
                        <div class="card-body">
                          <h5 class="card-title">{{$post->title}}</h5>

                           <div class="m-2 border">
                            <img  class="d-block w-100" src="{{$post->getMedia('temp_cover_image')[0]->getUrl()}}" alt="">
                           </div>
                          <p class="card-text">{!!  html_entity_decode($post->text_content) !!}</p>
                          <hr>
                           <h5>Alternative Images</h5>
                          <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @php
                                    $i=0;
                                @endphp
                                @foreach ($post->getMedia('temp_post_images') as $media)
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
                    </div>
                    @else
                    <div class="card" style="width: 50rem;">
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
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
