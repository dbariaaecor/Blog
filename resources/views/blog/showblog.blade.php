@extends('layouts.layout')

@section('content')
    <div class="container-fluid">

        <div class="row justify-content-center">

            <div class="col-md-10">
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
                    <a class="nav-link " style=" color:white; margin-bottom:5px; width:100px; position: relative; left:80%; border:1px solid black; background-color:rgb(120, 120, 216);" href="{{route('createblog')}}">
                        Add new Blog
                    </a>
                <div class="table-responsive">

                    @if ($posts->count() > 0)
                    <table class="table  table-hover">
                        <thead>
                          <tr>
                            <th>Blog</th>
                            <th>status</th>
                            <th>edit</th>
                            <th>delete</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($posts as $post)
                              <tr>
                                  <td>
                                      <div class="card" style="width: 20rem;">
                                          <div class="card-body">
                                            <h5 class="card-title" style="font-weight: bold;">{{$post->title}}</h5>
                                            <p class="card-text"><span>@php
                                              echo substr(html_entity_decode($post->text_content,ENT_QUOTES, 'UTF-8'),0,60)."....";
                                           @endphp</span></p>
                                            <span>created At: {{$post->created_at->toDateString()." | ".$post->created_at->toTimeString()}}</span>
                                            <br>
                                            <span>Apdated At: {{$post->updated_at->toDateString()." | ".$post->updated_at->toTimeString()}}</span>
                                          </div>
                                        </div>
                                  </td>
                                 <td style="vertical-align: middle;">
                                     @if ($post->post_Status==1)
                                        Pending
                                     @elseif($post->post_Status==0)
                                        In Draft
                                     @elseif($post->post_Status==2)
                                        published
                                    @elseif($post->post_Status==3 || $post->post_Status==5)
                                        Rejected
                                    @elseif($post->post_Status==4)
                                        Update-Approval(pending)
                                     @endif
                                 </td>
                                  <td style="vertical-align: middle;"><a href="{{route('edit',$post->slug)}}" class="btn btn-warning">Edit</a></td>
                                  <td style="vertical-align: middle;"><a onclick = "return confirm('Are you sure want To delete This Post?')" type="button" class="btn btn-danger delete" href="{{route('delete',$post->slug)}}">
                                      Delete
                                  </a></td>

                              </tr>
                          @endforeach
                        </tbody>
                      </table>
                    @else
                        <h2 style="text-align: center; margin-top:30px;">You Don't Have Any Post Yet. </h2>
                    @endif
                  </div>
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->


  <!-- Modal -->
  <div>

  </div>
@endsection

@section('links')
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('.delete').click(function(){
                $(this).show('#deleteblog');
                console.log('click');
            });
        });
    </script>
@endsection

