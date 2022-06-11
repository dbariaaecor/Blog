
    <div class="card">
        <div class="card-header">
          Blog Rejection
        </div>

        <div class="card-body">
          <h5 class="card-title">Your blog "{{$data['userpost']->title}}" is Rejected by {{$data['from']}}.</h5>

          <p class="card-text">{{$data['comment']}}</p>
          <a href="{{route('edit',$data['userpost']->slug)}}" class="btn btn-waring">Update Post</a>
          <a href="{{route('delete',$data['userpost']->slug)}}" class="btn btn-danger">Delete Post</a>
        </div>
        <h2>This is a email</h2>
      </div>

