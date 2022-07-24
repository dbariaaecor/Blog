<div class="card">
        <div class="card-header">
          Blog Rejection
        </div>
        <div class="card-body">
          <h5 class="card-title">Your blog "{{$data['userpost']->title}}" is  <span style="color: red;"><strong>Rejected</strong></span> by {{$data['superadmin']}}.</h5>
          <p class="card-text"><span style="color: red;"><strong>Reason:</strong></span> {{$data['comment']}}</p>
        </div>
        <div>Please Check Your Notification.</div>
    </div>
