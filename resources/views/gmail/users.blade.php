@extends('layouts.app')

@section('content')
@include('gmail.index')
<div id="main">

    <div class="container">
        <div class="row justify-content-center">
        <div class="col-md-12">
            <button class="btn btn-light" onclick="openNav()">
                <i class="fa fa-bars fa-xl"></i>
            </button>
            <div>
                <div class="container mt-2 bgc">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <h2>Users All Data</h2>
                    </div>
                </div>
            </div>
                <table class="table table-bordered shadow table-hover text-center w-auto">
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th width="280px">Action</th>
                    </tr>   
                    {{-- @dd($users);       --}}
                    @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }} </td>
                    <td>{{ $user->email }}</td>
                  
                    <td>
                        <form action="{{ route('user.destroy',$user->id) }}" method="Post">
                            <a class="btn btn-primary" href="{{ route('user.show',$user->email) }}">Show</a>
                            <a class="btn btn-warning" href="{{ route('user.edit',$user->id) }}">Edit</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach</tr>
            <a class="btn btn-primary" href="{{ route('label.index') }}">Back</a>
        </table>
        </div>
        <script>
             function openNav() {
                        document.getElementById("mySidenav").style.width = "250px";
                        document.getElementById("main").style.marginLeft = "250px";
                    }   

                    /* Set the width of the side navigation to 0 and the left margin of the page content to 0 */
                    function closeNav() {
                        document.getElementById("mySidenav").style.width = "0";
                        document.getElementById("main").style.marginLeft = "0";
                    }
        </script>
@endsection
   