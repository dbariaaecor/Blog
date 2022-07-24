@extends('layouts.layout')

@section('title','Edit Blog')
@section('page-css')
<style>
    /* p br,p:last-child{
        display:none;
    } */
    /*  <p><br data-cke-filler="true"></p> */
</style>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js" integrity="sha512-9UR1ynHntZdqHnwXKTaOm1s6V9fExqejKvg5XMawEMToW4sSw+3jtLrYfZPijvnwnnE8Uol1O9BcAskoxgec+g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
@section('pageheader','Edit Blog')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h4 class="m-2" style="font-weight: bold;">All Blogs</h4>
                <hr>
                <form action="{{route('blog.update',$post->slug)}}" method="post" enctype="multipart/form-data">
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
                       @if ($post->post_Status==6)
                        <div class="row">
                            <div class="col-3 delcovimg">
                                {{-- @dd($post->getMedia('cover_image')->toArray()) --}}
                                @if ($post->getMedia('temp_cover_image')->toArray()!=null)
                                <button type="button" class="close covimgclose" data-id="{{$post->getMedia('temp_cover_image')[0]->uuid}}" aria-label="Close">
                                    X
                                </button>

                                <img src="{{$post->getMedia('temp_cover_image')[0]->getUrl()}}" class="img-thumbnail">
                                <span>Image:{{$post->getMedia('temp_cover_image')[0]->name}}</span>
                            </div>
                        </div>
                            @endif
                        @elseif ($post->post_Status!=3)
                        <div class="row">
                            <div class="col-3 delcovimg">
                                {{-- @dd($post->getMedia('cover_image')->toArray()) --}}
                                @if ($post->getMedia('cover_image')->toArray()!=null)

                                <button type="button" class="close covimgclose" data-id="{{$post->getMedia('cover_image')[0]->uuid}}" aria-label="Close">
                                    X
                                </button>
                                <img src="{{$post->getMedia('cover_image')[0]->getUrl()}}" class="img-thumbnail">
                                <span>Image:{{$post->getMedia('cover_image')[0]->name}}</span>
                            </div>
                        </div>
                            @endif
                        @else
                        <div class="row">
                            <div class="col-3 delcovimg">
                                {{-- @dd($post->getMedia('cover_image')->toArray()) --}}
                                @if ($post->getMedia('temp_cover_image')->toArray()!=null)
                                <button type="button" class="close covimgclose" data-id="{{$post->getMedia('temp_cover_image')[0]->uuid}}" aria-label="Close">
                                    X
                                </button>

                                <img src="{{$post->getMedia('temp_cover_image')[0]->getUrl()}}" class="img-thumbnail">
                                <span>Image:{{$post->getMedia('temp_cover_image')[0]->name}}</span>
                            </div>
                        </div>
                        @endif
                       @endif
                    </div>
                    <div class="form-group">
                        <label for="">Tags</label>
                        <select multiple id="tags" data-role="tagsinput" name="tags[]" class="form-control">
                            {{-- <option value='{{$tag["slug"]}' @php in_array($tag->name,$tags) ? "selected" : "" ; @endphp>{{$tag['slug']}}</option> --}}
                        </select>
                        @error('tags')
                            <span style="color:red;">{{$message}}</span>
                         @enderror
                        {{-- <label for="">Tags (Comma-Seprate)</label>
                        <input type="text" name="tags" placeholder="tag1,tag2" class="form-control" value="{{$tags}}"> --}}
                        @error('tags')
                        <span style="color: red;">{{$message}}</span>
                    @enderror
                    <div class="form-group">
                        <label for="postcontent">Content</label>
                        @error('postcontent')
                            <span style="color:red;">{{$message}}</span>
                        @enderror
                        {{-- <textarea type="text" rows="10" name="postcontent" id="postcontent" class="form-control ckeditor" value={{$post->text_content}}></textarea> --}}
                        <textarea   name="postcontent" id="postcontent" class="form-control ckeditor" >{!! $post->text_content !!}</textarea>
                        {{-- {!! $post->text_content !!} --}}
                    </div>
                    <div class="form-group" id="datetime">
                        <label for="">Schedule</label>
                        <input type="datetime-local" name="scheduletime"  min="{{ date('Y-m-d\TH:i',strtotime(now()))}}" class="form-control">
                        @error('scheduletime')
                            <span style="color: red;">{{$message}}</span>
                        @enderror
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
                            @if ($post->post_Status==3)
                                    @foreach ($post->getMedia('temp_post_images') as $img )

                                    <div class="col-2 imgcon">
                                        <button type="button" class="close imgclose" data-id="{{$img->uuid}}" aria-label="Close">
                                            X
                                        </button>
                                        <img src="{{$img->getUrl()}}" class="img-thumbnail">
                                        {{$img->name}}
                                    </div>

                                    @endforeach
                            @elseif ($post->post_Status==6)
                            @foreach ($post->getMedia('temp_post_images') as $img )
                            <div class="col-2 imgcon">
                                <button type="button" class="close imgclose" data-id="{{$img->uuid}}" aria-label="Close">
                                    X
                                </button>
                                <img src="{{$img->getUrl()}}" class="img-thumbnail">
                                {{$img->name}}
                            </div>
                            @endforeach
                            @else
                                    @foreach ($post->getMedia() as $img )
                                    <div class="col-2 imgcon">
                                        <button type="button" class="close imgclose" data-id="{{$img->uuid}}" aria-label="Close">
                                            X
                                        </button>
                                        <img src="{{$img->getUrl()}}" class="img-thumbnail">
                                        {{$img->name}}
                                    </div>
                                    @endforeach
                            @endif
                        </div>
                    </ul>
                    @if($post->post_Status==0)
                    <div class="row">
                    </div>
                    <div class="col-md-12">
                        <div class="row justify-content-center">
                            <div class="col-md-2 form-group ">
                                <input type="submit" name="saveasdraft" class="btn btn-warning" value="Save as Draft">
                            </div>
                            <div class="btn-group col-md-2 form-group ">
                                <input type="submit" class="btn btn-success" id="puborsch">
                                <select id="schorpub" name="pubsche" value="{{old('schorpub')}}">
                                    <option value="publish" @if (old('pubsche')=='publish')
                                            selected
                                    @endif id="pub">publish</option>
                                    <option value="schedule" id="sch"  @if (old('pubsche')=='schedule')
                                    selected
                             @endif>schedule</option>
                                </select>
                              </div>
                        </div>
                        {{-- <input type="submit" name="publish" class="ms-4 btn btn-success" value="Publish"> --}}
                    </div>
                    @else
                        <div class="form-group col-md-3 offset-md-4 mt-2 ">
                            <input type="submit" name="update" class="form-control btn btn-warning" value="Update">
                        </div>
                    @endif

                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-js')

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
        $('.imgclose').click(function(){
            if(confirm('Are you sure want to delete This image? want be able to recover')){
                var uuid = $(this).data('id');
                var postslug = "{{$post->slug}}";
                var x = this;
                // console.log(uuid+"|"+postslug);
                $.ajax({
                    url:"/delete/"+postslug+"/"+uuid,
                    type:"get",
                    data:{slug:postslug,id:uuid},
                    success:function(data){
                        if(data){
                            $(x).parents('.imgcon').hide();
                        }
                    }
                });
            }

        });

        $('.covimgclose').click(function(){
            if(confirm('Are you sure want to delete This image? want be able to recover')){
                var uuid = $(this).data('id');
                var postslug = "{{$post->slug}}";
                var x = this;
                // console.log(uuid+"|"+postslug);
                $.ajax({
                    url:"/delete/"+postslug+"/"+uuid,
                    type:"get",
                    data:{slug:postslug,id:uuid},
                    success:function(data){
                        if(data){
                            $(x).parents('.delcovimg').hide();
                        }
                    }
                });
            }

        });
</script>
<script>
    $(document).ready(function() {
    //set default submit at publish


    $('#puborsch').attr({'name':'publish','value':'publish'});
    $('#datetime').hide();

   //set values based on drop down
        $('#schorpub').change(function(){
           var value = $(this).val()
           if(value=='publish'){
                $('#puborsch').attr({'name':'publish'});
                $('#puborsch').val('publish');
                $('#datetime').hide();
           }else{
                $('#puborsch').attr({'name':'schedule'});
                $('#puborsch').val('schedule');
                $('#datetime').show();
           }
        });
        $(window).on('load', function(){
           if($('#schorpub').val()=='schedule'){
            $('#puborsch').attr({'name':'schedule'});
                $('#puborsch').val('schedule');
                $('#datetime').show();
           }
        });

    //tags

     $('#tags').select2();
     $.ajax({
         url:'/edittags/'+"{{$post->slug}}",
         type:'get',
         success:function(data){
             if(data!=""){
                // console.log(data);
                $('#tags').append(data)
             }
         }
     });




    });
</script>
<script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
{{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> --}}
<script>

ClassicEditor.create( document.querySelector('#postcontent'))
.then( editor => {

    console.log( editor );
    // editor.setData( '<p>This is a tag: <code>&lt;xml&gt;</code>.</p>' );
}).catch( error => {
    console.error();
});


//  tinymce.init({
//     selector:'#postcontent',
//     // plugins: 'powerpaste advcode table lists checklist',
//     // entity_encoding : "raw",
//     // entities:'169,copy,8482,trade,ndash,8212,mdash,8216,lsquo,8217,rsquo,8220,ldquo,8221,rdquo,8364,euro',
//     toolbar: 'undo redo | blocks| bold italic | bullist numlist checklist | code | table',

// });
</script>
@endsection


