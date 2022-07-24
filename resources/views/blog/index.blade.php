@extends('layouts.layout')
@section('title','Index')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h2>{{$userslug." blogs"}}</h2>
                <hr>
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">Blog</th>
                        <th scope="col">Read</th>
                      </tr>
                    </thead>
                    <tbody>
                        @if ($posts!=null)
                            @foreach ($posts as $post )
                                <tr>
                                    <td>{{$post->title}}</td>
                                    <td scope="col"><a href="{{route('userblogs',[$userslug,$post->slug])}}">Read</a></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
@endsection
