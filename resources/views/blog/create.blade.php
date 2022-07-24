@extends('layouts.layout')

@section('title','creatBlog')


@section('page-css')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js" integrity="sha512-9UR1ynHntZdqHnwXKTaOm1s6V9fExqejKvg5XMawEMToW4sSw+3jtLrYfZPijvnwnnE8Uol1O9BcAskoxgec+g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
@section('pageheader','Add Blog')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-10">
                <form action="{{route('blog.store')}}" method="post" enctype="multipart/form-data">
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
                        <label for="">Tags</label>
                        <select multiple id="tags" data-role="tagsinput" name="tags[]" class="form-control">

                        </select>

                        @error('tags')
                            <span style="color:red;">{{$message}}</span>
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
                    <div class="form-row col-md-6 offset-md-3">
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

@section('page-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/js/bootstrap-select.min.js" integrity="sha512-yrOmjPdp8qH8hgLfWpSFhC/+R9Cj9USL8uJxYIveJZGAiedxyIxwNw4RsLDlcjNlIRR4kkHaDHSmNHAkxFTmgg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>

 ClassicEditor.create( document.querySelector('#postcontent'))
 .then( editor => {

     console.log( editor );
 }).catch( error => {
     console.error();
 });

// tinymce.init({
//      selector:'#postcontent',
//      plugins: 'powerpaste advcode table lists checklist',
//      toolbar: 'undo redo | blocks| bold italic | bullist numlist checklist | code | table'
//    });
</script>

<script>
    $(document).ready(function() {
         $('#tags').select2();
         $.ajax({
        url:'/tags',
        type:'get',
        success:function(data){
            if(data!=""){
               $('#tags').append(data)
            }
        }
     });
    var tags = "";
    $('#tags').on('change',function(){
        var tag = $(this).val();
        if(tags.length==0){
            tags = tag;
        }else if(tags.length>0){
            tags = tags +","+ tag;
        }
        $('#inputtag').val(tags);
    });
    });

</script>
@endsection

