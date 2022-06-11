@extends('layouts.layout')

@section('headerlink')
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --}}

@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-8">
                @foreach (auth()->user()->notifications as $notification )
                <div class="alert alert-primary" role="alert">
                     A Post <strong><a href="">{{$notification->data['post']['title']}}</a></strong> is requestd by <strong><a href="">{{$notification->data['email']}}</a></strong>
                     @if ($notification->read_at==null)
                        <a href="{{route('approve',[$notification->data['post']['slug'],$notification->id])}}" class="btn btn-primary">Approve</a>
                        <button type="button" class="btn btn-danger cancel" data-slug = "{{$notification->data['post']['slug']}}" data-id="{{$notification->id}}" data-toggle="modal" data-target="#cancel">
                            Cancel
                        </button>
                      @else
                        (Read)
                     @endif

                  </div>
                @endforeach
            </div>
        </div>
    </div>

      <!-- Modal -->
      <div class="modal fade" id="cancel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" id="cc" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Comment On Post</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                        <textarea name="comment"  class="form-control"  cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="button"  class="form-control btn btn-primary" id="sendcomment" value="Comment">
                    </div>
               </form>
            </div>

          </div>
        </div>
      </div>
@endsection

@section('links')
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>

    $(document).ready(function(){
        $('.cancel').click(function(){

             var slug = $(this).data('slug');
             var id = $(this).data('id');

            $('#cancel').attr('data-dismiss','');
            $('#cancel').attr('aria-label','');
            $('#cancel').removeClass('close');
             $('#sendcomment').click(function(){
                var comment = $('textarea').val();
                var x = this;
                $.ajaxSetup({
                    headers:
                    { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                 });

                 $.ajax({
                     url:'/cancel/'+slug+'/'+id,
                     type:'post',
                     data:{slug:slug,id:id,comment:comment},
                     success:function(data){
                        if(data){

                            $('#cancel').removeClass('show');
                            $('.modal-backdrop').remove();
                            location.reload();
                        }

                     },
                     error:function(data){

                     }
                 })
            });
        });
    });
    </script>

@endsection

