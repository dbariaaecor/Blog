@extends('layouts.layout')

@section('title','profile')
@section('headerlink')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    @section('pageheader','Profile Page')
    <div class="container">
        <div class="row  justify-content-center">
            <div class="col-md-8 ">
                <div class="cover-img "  style="border:3px solid gray; width:100%; height:300px; background-color:FFFFFF; box-shadow:1px 1px 1px 1px gray; ">
                        <img src="{{asset('webimages/profile.jpg')}}" alt="" width="30%" height="80%" style="position:relative; left:35%; top:10%; border:2px solid gray; border-radius:50%;">
                </div>
                <div>
                    <div class="card mt-5">
                        <h5 class="card-header">Basic Info</h5>
                        <div class="card-body" style="card-bod">
                           <div>Name : <span>{{Auth::user()->name}}</span></div>
                           <div>Username : <span>{{Auth::user()->username}}</span></div>
                           <div>Email : <span>{{Auth::user()->email}}</span></div>
                           <div>Phone : <span>{{Auth::user()->phone}}</span></div>
                           <button type="button" class="btn btn-primary" data-toggle="modal" data-slug="{{Auth::user()->username}}" data-target="#basicdata">
                            Edit
                          </button>
                        </div>
                      </div>
                </div>
                <div>
                    <div class="card mt-5">
                        <h5 class="card-header">Professional Info</h5>
                        <div class="card-body" style="card-bod">
                           <div>Profession :</div>
                           <div>Profession Role :</div>
                           <div>Technologies :</div>
                           <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#professionaldata">
                            Edit
                          </button>
                      </div>
                </div>

                <div>
                    <div class="card mt-5">
                        <h5 class="card-header">About You</h5>
                        <div class="card-body" style="card-bod">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#aboutdata">
                                Edit
                              </button>
                        </div>
                      </div>
                </div>

            </div>
        </div>
    </div>


<!-- Button trigger modal -->


  <!--Basic Modal -->
  <div class="modal fade" id="basicdata" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Basic Information</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-group" value="{{Auth::user()->name}}">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="number" name="phone" id="phone" class="form-group" value="{{Auth::user()->phone}}">
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="basicdataupdate">Save changes</button>
        </div>
      </div>
    </div>
  </div>

   <!--Professional Modal -->
   <div class="modal fade" id="professionaldata" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Professional Info</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
           <form action="">
                <div class="form-group">
                    <label for=""></label>
                </div>
           </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

   <!--About Modal-->
   <div class="modal fade" id="aboutdata" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">About You</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
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
{{-- Edit Profile Data --}}

<script>
    $(document).ready(function(){
        $('#basicdataupdate').click(function(){
            var name = $('#name').val();
            var phone = $('#phone').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'post',
                url:'{{route("profileupdate")}}',
                data:{name:name,phone:phone},
                success:function(data){
                    console.log(data);
                }
            });
        });
    });
</script>
@endsection
