@extends('layouts.layout')

@section('title','Update Profile')
@section('page-css')
  <style>
    .tech,.level,.exper{
        width: 100px;
    }

  </style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <a href="{{route('user.userindex',$author->username)}}" class="btn btn-secondary">back</a>
                <form action="{{route('user.professionalprofileupdate',$author->username)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <table class="table">
                        <tr>
                            <th class="th-sm">Technology</th>
                            <th>Level</th>
                            <th>Expreience</th>
                            <th>Description</th>
                            <th></th>
                        </tr>
                        <tbody id="multiForm">
                            @if (Auth::user()->experiance==null)
                                <tr>
                                    <td class="tech">
                                        <input type="text" name="tehnology[]" class="form-control">
                                        @error('tehnology')
                                        <span style="color: red;">{{$message}}</span>
                                        @enderror
                                    </td>
                                    <td class="level">
                                        <select class="form-control" name="level[]">
                                            <option value="beginner">Beginner</option>
                                            <option value="intermediate">Intermediate</option>
                                            <option value="advance">Advance</option>
                                        </select>
                                        @error('level')
                                            <span style="color: red;">{{$message}}</span>
                                        @enderror
                                    </td>
                                    <td class="exper">
                                        <input type="number" height="200px" name="expreience[]" class="form-control">
                                        @error('expreience')
                                        <span style="color: red;">{{$message}}</span>
                                        @enderror
                                    </td>
                                    <td class="desc">
                                        <textarea name="desc" rows="5" cols="20" class="form-control" ></textarea>
                                        @error('desc')
                                        <span style="color: red;">{{$message}}</span>
                                        @enderror
                                    </td>
                                </tr>
                            @else
                                 @foreach (json_decode(Auth::user()->experiance,true) as $exp)
                                    <tr height="100px" >
                                        <td  class="tech ">
                                            <input type="text" name="tehnology[]"  class="form-control" value="{{$exp['technology']}}">
                                                @error('tehnology')
                                                <span style="color: red;">{{$message}}</span>
                                                 @enderror
                                        </td>
                                        <td class="level">
                                            <select class="form-control" name="level[]" >
                                                <option value="beginner" @if ($exp['level']=="beginner")
                                                    selected @endif>Beginner</option>
                                                <option value="intermediate" @if ($exp['level']=="intermediate")
                                                selected @endif>Intermediate</option>
                                                <option value="advance" @if ($exp['level']=="advance")
                                                selected @endif>Advance</option>

                                            </select>
                                            @error('level')
                                                <span style="color: red;">{{$message}}</span>
                                                 @enderror
                                        </td>
                                        <td class="exper">
                                            <input type="number" name="expreience[]" class="form-control"  value="{{$exp['expreience']}}">
                                            @error('expreience')
                                                <span style="color: red;">{{$message}}</span>
                                                 @enderror
                                        </td>
                                        <td> <textarea name="desc"  rows="5" cols="20" class="form-control" >{{$exp['des']}}</textarea></td>
                                        <td ><input type="button" width="20px" class="btn btn-danger remove form-control"  value="remove" ></td>
                                        </tr>
                                 @endforeach
                            @endif
                        </tbody>

                    </table>
                    <div class="form-group col-2">
                        <input type="button" value="Add" class="form-control btn btn-primary" id="add">
                    </div>
                    <div class="form-group">
                        <label for="cv">CV</label>
                        <input type="file" class="form-control" name="cvfile" id="cv">
                        @error('cv')
                            <span style="color: red;">{{$message}}</span>
                        @enderror
                    </div>
                    <br>
                    <div class="form-group col-md-2 offset-md-4">
                        <input type="submit" name="update" class="form-control btn btn-primary" value="update">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page-js')
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function(){

        $('#add').click(function(){
            $('#multiForm').append('<tr><td class="tech"><input type="text" name="tehnology[]" class="form-control"></td><td class="level"><select name="level[]" class="form-control"><option value="beginner">Beginner</option><option value="intermediate">Intermediate</option><option value="advance">Advance</option></select></td><td class="exper"><input type="number" name="expreience[]" class="form-control"></td><td> <textarea name="desc" rows="5" cols="20" class="form-control"></textarea></td><td><input type="button" class="btn btn-danger remove form-control" value="remove"></td></tr>')
        });
        $(document).on('click', '.remove', function () {
            $(this).parents('tr').remove();
    });
    });
</script>

@endsection

