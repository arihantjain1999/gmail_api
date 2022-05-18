@extends('layouts.app')
@section('content')
    @include('gmail.index')
    <div class="container  mt-2">
        <div id="main">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <button class="btn btn-light" onclick="openNav()">
                        <i class="fa fa-bars fa-xl"></i>
                    </button>
                    <div class="pull-left">
                        <h2>{{ $users->name }} User Details</h2>
                    </div>
                    <div class="pull-right">
                    </div>
                </div>
            </div>
            @if (session('status'))
                <div class="alert alert-success mb-1 mt-1">
                    {{ session('status') }}
                </div>
            @endif
            <form action="{{ route('user.update', $users->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Full Name: {{ $users->name }}</strong>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Dob: {{ $users->email }}</strong>
                        </div>
                    </div>
                    {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Password: {{$users->password}}</strong>
                        </div>
                    </div> --}}
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Provider: {{ $users->provider }}</strong>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Provider: {{ $users->user_type }}</strong>
                        </div>
                    </div>
                </div>
            </form>
            @php
                $userType = Auth::user()->user_type;
            @endphp
            <a class="btn btn-primary" href="{{ route('label.index') }}">Back</a>
            @if ($userType == 'User')
                <a class="btn btn-warning" href="{{ route('user.editUser', $users->email) }}">Edit</a>
            @else
                {{-- @dd($userType); --}}

                <a class="btn btn-warning" href="{{ route('user.edit', $users->id) }}">Edit</a>
                <form action="{{ route('user.destroy', $users->id) }}" method="Post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            @endif

        </div>
    </div>
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "200px";
            document.getElementById("main").style.marginLeft = "200px";
        }

        /* Set the width of the side navigation to 0 and the left margin of the page content to 0 */
        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }
    </script>
@endsection
