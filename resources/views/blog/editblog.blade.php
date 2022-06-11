@extends('layouts.layout')

@section('title','creatBlog')

@section('content')
@section('pageheader','Edit Blog')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-10">
                <h4 class="m-2" style="font-weight: bold;">All Blogs</h4>
                <hr>
                <form action="{{route('update',$post->slug)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="from-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{$post->title}}">
                        @error('title')
                            <span style="color:red;">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Cover Img</label>
                        <input type="file" name="cover_img" class="form-control">
                        @error('cover_img')
                         <span style="color:red;">{{$message}}</span>
                        @enderror
                        <div class="row">
                            <div class="col-3">
                                <img src="{{$post->getMedia('cover_image')[0]->getUrl()}}" class="img-thumbnail">
                            </div>
                        </div>
                        <span>Image:{{$post->getMedia('cover_image')[0]->name}}</span>

                    </div>
                    <div class="form-group">
                        <label for="">Tags (Comma-Seprate)</label>
                        <input type="text" name="tags" placeholder="tag1,tag2" class="form-control">
                        @error('tags')
                        <span style="color: red;">{{$message}}</span>
                    @enderror
                    <div class="form-group">

                        <label for="postcontent">Content</label>
                        @error('postcontent')
                            <span style="color:red;">{{$message}}</span>
                        @enderror
                        <textarea type="text" rows="10" name="postcontent" id="postcontent" class="form-control ckeditor" value={!!  html_entity_decode($post->text_content) !!}></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Alternative Images</label>
                        <input type="file" name="post_img[]" class="form-control" multiple>
                        @error('post_img')
                        <span style="color:red;">{{$message}}</span>
                    @enderror
                    </div>
                    <span>Post Images</span>
                    <ul>

                        <div class="row">
                            @foreach ($post->getMedia() as $img )
                                <div class="col-2">
                                    <img src="{{$img->getUrl()}}" class="img-thumbnail">
                                    {{$img->name}}
                                </div>

                            @endforeach
                        </div>
                    </ul>
                        <div class="form-group">
                            <input type="submit" name="update" class="form-control btn btn-warning" value="Update">
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('links')
<script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
<script>

ClassicEditor.create( document.querySelector( '#postcontent'))
.then( editor => {
    console.log( editor );
})
    .catch( error => {
    console.error( error );
});
</script>
@endsection
