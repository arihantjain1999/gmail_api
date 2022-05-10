@extends('layouts.app')
@section('content')
@include('gmail.index')
<div id="main">

    <div class="container mt-2">
        <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left mb-2">
                        <h2>Add User</h2>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('user.index') }}" enctype="multipart/form-data"> Back</a>
                    </div>
                </div>
            </div>
           
            @if(session('status'))
                <div class="alert alert-success mb-1 mt-1">
                    {{ session('status') }}
                </div>
            @endif
                {!!Form::open(['route' => 'user.store'] , ['method'=>'POST' , 'enctype'=>'multipart/form-data'])!!}
                @csrf
                {{-- @include('contact.form_c') --}}
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>User Name:</strong>
                        <div class="col-sm-5">
                            <input type="text" name="name">
                        </div>
                        @error('user')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Email:</strong>
                            <div class="col-sm-5">
                                <input type="Email" name="email" >
                            </div>
                            @error('email')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                    </div>
                </div>
                @if (!empty($err))
                    {{$err}}
                @endif  
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Password:</strong>
                        <div class="col-sm-5">
                            <input type="password" name="password" >
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Comfir Password:</strong>
                        <div class="col-sm-5">
                            <input type="password" name="c_password" >
                        </div>
                    </div>
                </div>
                </div>
               
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>User Type:</strong>
                        <div class="col-sm-5">
                            <select name="user_type">  
                                <option value="Admin">Admin</option>
                                <option value="">User</option>
                            </select>
                        </div>
                    </div>
                </div>
                </div>
               

                {!!Form::submit('Create User' , ['class'=>'btn btn-primary my-3']);!!}

                {!! Form::close() !!}
            </div>
@endsection