@extends('layouts.layout')

@section('title','creatBlog')


@section('headerlink')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js" integrity="sha512-9UR1ynHntZdqHnwXKTaOm1s6V9fExqejKvg5XMawEMToW4sSw+3jtLrYfZPijvnwnnE8Uol1O9BcAskoxgec+g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@endsection

@section('content')
@section('pageheader','Add Blog')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-10">
                <form action="{{route('store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="from-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" class="form-control" value=" {{old('title')}}" placeholder="Post Title">

                        @error('title')
                            <span style="color: red;">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Cover Img</label>
                        <input type="file" name="cover_img" class="form-control">
                        @error('cover_img')
                            <span style="color: red;">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Tags (Comma-Seprate)</label>
                        <input type="text" name="tags" placeholder="tag1,tag2" class="form-control">
                        @error('tags')
                        <span style="color: red;">{{$message}}</span>
                    @enderror
                    </div>

                    <div class="form-group">
                        <label for="postcontent">Content</label>
                        <textarea type="text" rows="20" name="postcontent" id="postcontent" class="form-control ckeditor" placeholder="Post Content"></textarea>
                        @error('postcontent')
                            <span style="color: red;">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Alternative Images</label>
                        <input type="file" name="post_img[]" class="form-control" multiple>
                        @error('post_img')
                            <span style="color: red;">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <input type="submit" name="draft" class="form-control btn btn-warning" value="save as draft">
                        </div>
                        <div class="col">
                            <input type="submit" name="publish" class="form-control btn btn-primary" value="publish now">
                        </div>
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

