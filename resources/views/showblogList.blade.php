@extends('layouts.layout')
@section('title','dashbord')
<body>

    @section('content')
    @section('pageheader','All Blogs')
    <div class="container-fluid">
        <div class="row">
            @if (session()->has('message'))
                <h4><span style="color:green;">{{session()->get('message')}}</span></h4>
            @endif

           @foreach ($posts as $post )
               <div class="card m-2" style="width: 22rem;">
                  @foreach ($post->getMedia('cover_image') as $img)
                  <img class="card-img-top" src="{{$img->getUrl()}}" alt="Card image cap">

                  @endforeach
                   <div class="card-body">
                   <h5 class="card-title">{{$post->title}}</h5>
                   <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>

                   @if ($post->post_Status==0)
                    <a href="{{route('addPostContent',$post->slug)}}" class="btn btn-primary btn-sm">Add Content</a>
                   @elseif ($post->post_Status==1)
                   <a href="{{route('addPostContent',$post->slug)}}" class="btn btn-primary btn-sm">Publish ?</a>
                   @else ($post->post_Status==2)
                   <span>In Padning</span>
                   @endif
                   @if ($post->post_Status!=0)

                   <a href="{{route('showComplePost',$post->slug)}}" class="btn btn-success btn-sm">Show Post</a>
                   @endif

                   <a href="{{route('deletepost',$post->slug)}}" class="btn btn-danger btn-sm">Delete Post</a>
                   </div>
               </div>
           @endforeach
         </div>
       </div>
    @endsection

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
