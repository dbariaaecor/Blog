@extends('layouts.layout')

@section('title','Update Profile')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <form action="{{route('profileupdate',$author->username)}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" class="form-control" value="{{$author->name}}">
                        @error('name')
                            <span style="color: red;">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Phone</label>
                        <input type="number" name="phone" class="form-control" value="{{$author->phone}}">
                        @error('phone')
                        <span style="color: red;">{{$message}}</span>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Technologies</label>
                    </div>
                    <table>
                        <tr>
                            <th>Technology</th>
                            <th>Level</th>
                            <th>Expreience</th>
                            <th></th>
                        </tr>
                        <tbody id="multiForm">
                            @if (Auth::user()->experiance==null)
                                <tr>
                                    <td>
                                        <input type="text" name="tehnology[]" class="form-control">
                                        @error('tehnology')
                                        <span style="color: red;">{{$message}}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <select class="form-control" name="level[]">
                                            <option value="beginner">Beginner</option>
                                            <option value="intermediate">Intermediate</option>
                                            <option value="advance">Advance</option>
                                        </select>
                                        @error('level')
                                        <span style="color: red;">{{$message}}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" name="expreience[]" class="form-control">
                                        @error('expreience')
                                        <span style="color: red;">{{$message}}</span>
                                        @enderror
                                    </td>

                                </tr>
                            @else
                                 @foreach (json_decode(Auth::user()->experiance,true) as $exp)
                                    <tr>
                                        <td>
                                            <input type="text" name="tehnology[]" class="form-control" value="{{$exp['technology']}}">
                                                @error('tehnology')
                                                <span style="color: red;">{{$message}}</span>
                                                 @enderror
                                        </td>
                                        <td>
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
                                        <td>
                                            <input type="number" name="expreience[]" class="form-control" value="{{$exp['expreience']}}">
                                            @error('expreience')
                                                <span style="color: red;">{{$message}}</span>
                                                 @enderror
                                        </td>
                                        <td><input type="button" class="btn btn-danger remove form-control" value="remove" ></td>
                                        </tr>
                                 @endforeach
                            @endif
                        </tbody>

                    </table>
                    <br>
                    <div class="form-group col-2">
                        <input type="button" value="Add" class="form-control btn btn-primary" id="add">
                    </div>
                    <div class="form-group">
                        <label for="descrition">About You</label>
                        <textarea name="description" id="descrition" class="form-control" rows="5" >{{$author->Auth_Description}}</textarea>
                        @error('description')
                        <span style="color: red;">{{$message}}</span>
                         @enderror
                    </div>
                    <div class="form-group">
                        <input type="submit" name="update" class="form-control btn btn-primary" value="update">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('links')
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function(){

        $('#add').click(function(){
            $('#multiForm').append('<tr><td><input type="text" name="tehnology[]" class="form-control"></td><td><select name="level[]" class="form-control"><option value="beginner">Beginner</option><option value="intermediate">Intermediate</option><option value="advance">Advance</option></select></td><td><input type="number" name="expreience[]" class="form-control"></td><td><input type="button" class="btn btn-danger remove form-control" value="remove"></td></tr>')
        });
        $(document).on('click', '.remove', function () {
            $(this).parents('tr').remove();
    });
    });
</script>

@endsection

