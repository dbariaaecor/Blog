@extends('layouts.layout')

@section('title','addBlog')

@section('content')
    <div class="container">
        @section('pageheader','Add Blog')
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <h5 class="card-header">New Blog</h5>
                    <div class="card-body" style="card-bod">
                        <form action="{{route('CreatePostTitle')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group m-2">
                              <label for="title" class="m-2">Title</label>
                              <input type="title" class="form-control" id="title" name="title" placeholder="xyz blog">
                              </div>

                            <div class="form-group m-2">
                                <label for="">Cover img</label>
                                <input id="cover_img" type="file" name="cover_img" class="form-control" multiple>
                            </div>

                            <div class="form-group m-2">
                                <input type="submit" name="post_title" class="form-control btn btn-primary" value="Add Title">
                            </div>

                          </form>
                    </div>
                  </div>
            </div>
        </div>
    </div>



@endsection
