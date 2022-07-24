@extends('layouts.layout')
@section('title','Show Draft Blog')
@section('content')
    <div class="container-fluid">

        <div class="row">

            <div class="col-md-8">
                <h4 class="m-2">All Blogs</h4>
                <hr>
                @if (session()->has('message'))
                <div class="alert alert-success" role="alert">
                    {{session()->get('message')}}
                  </div>
                @endif
                @if (session()->has('delete'))
                <div class="alert alert-danger" role="alert">
                    {{session()->get('delete')}}
                  </div>
                @endif
                    <a class="nav-link " style=" color:white; margin-bottom:5px; width:100px; position: relative; left:80%; border:1px solid black; background-color:rgb(120, 120, 216);" href="{{route('blog.createblog')}}">
                        Add new Blog
                    </a>
                    <div class="row">
                        <nav class="navbar navbar-expand-lg navbar-light bg-light">

                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                              <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                              <div class="navbar-nav">

                                <a class="nav-item nav-link" href="{{route('blog.showallblog')}}">Published</a>
                                <a class="nav-item nav-link active" href="{{route('blog.draftshowallblog')}}">Drafted</a>

                              </div>
                            </div>
                          </nav>
                    </div>
                <div class="row">

                    @if ($posts->count() > 0)

                    @foreach ($posts as $post)

                     @if ($post->post_Status==0)
                        <div class="card border-dark mb-3" style="max-width: 100%;">
                            <div class="card-header bg-transparent border-dark"><h3>{{$post->title}}</h3>
                            </div>
                            <div class="card-body text-dark">
                                <p class="card-text">
                                    {!! $post->preview_content !!}
                            </p>
                            </div>
                            <div class="card-footer bg-transparent border-dark">
                                <div class="wrapper"><span ><strong>Draft</strong> at {{$post->updated_at->toDateString()}} | {{$post->updated_at->toTimeString()}} ({{$post->updated_at->diffForHumans()}})</span></div>
                                <button type="button" class="btn btn-success publish_btn" data-slug="{{$post->slug}}"  data-toggle="modal" data-target="#publishing">
                                    publish
                                </button>
                                <a href="{{route('blog.edit',$post->slug)}}" class="btn btn-warning">Edit</a>
                                <a onclick="return confirm('Are you sure want to delete?')" href="{{route('blog.delete',$post->slug)}}"  class="btn btn-danger delete-post">Delete</a>
                            </div>
                        </div>
                     @endif

                    @endforeach
                    @else
                        <h2 style="text-align: center; margin-top:30px;">You Don't Have Any Post Yet. </h2>
                    @endif
                  </div>
            </div>
        </div>
    </div>
  <div>
  </div>
  <!-- Button trigger modal -->


  <!-- Modal -->
  <div class="modal fade" id="publishing"  tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Publishing</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

            <div class="form-group">
                <input type="datetime-local" class="form-control" id="schedule_date" min="{{ date('Y-m-d\TH:i',strtotime(now()))}}"  class="datetime" value="{{ date('Y-m-d\TH:i',strtotime(now()))}}">
                {{-- 2017-06-01T08:30 --}}
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary pub"  >Publish Now</button>
          <button type="button" class="btn btn-primary scpub"  style="display: none;">Scheduel publishing</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('page-js')
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    @if ($posts->count() > 0)
    <script>

        $(document).ready(function(){

            var slug = "";
            // $('.delete-post').click(function(){
            //     confirm('Sure want to delete this post?');
            // });

            $(".publish_btn").click(function(){
                slug = $(this).data('slug');

                $('.scpub').hide();
                $('.pub').show();
                $('#schedule_date').val("{{ date('Y-m-d\TH:i',strtotime(now()))}}");
            });

            $('#schedule_date').change(function(){
                 $('.scpub').show();
                 $('.pub').hide();
                var sdate =  $(this).val();
                var ndate = "{{ date('Y-m-d\TH:i',strtotime(now()))}}";
                if(sdate==ndate){
                    $('.scpub').hide();
                    $('.pub').show();
                }
            });

            $(document).on('click','.scpub',function(){

                var sslug = slug;
                var datetime = $('#schedule_date').val();
                console.log(datetime);
                $.ajax({
                    url:'/publish/'+sslug,
                    type:'get',
                    data:{slug:slug,datetime:datetime},
                    success:function(data){

                    }
                });
                $('.modal-backdrop').remove();
                $('#publishing').hide();
            })
            $(document).on('click','.pub',function(){

                var sslug = slug;
                console.log('in');
                $.ajax({
                    url:'/publishnow/'+sslug,
                    type:'get',
                    data:{slug:slug},
                    success:function(data){

                    }
                });
                $('.modal-backdrop').remove();
                $('#publishing').hide();
            })
            // $('#scpub').click(function(){
            //     var slug = "{{$post->slug}}";
            //     var datetime = $('.datetime').val();
            //     // $.ajax({
            //     //     url:'/publish/'+slug,
            //     //     type:'get',
            //     //     data:{slug:slug,datetime:datetime},
            //     //     success:function(data){

            //     //     }
            //     // });
            //     $('.modal-backdrop').remove();
            //     $('#publishing').hide();

            // });

        });

    </script>
    @endif

@endsection


