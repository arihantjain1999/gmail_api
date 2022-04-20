@extends('layouts.app')
@section('content')

<h1>hello</h1>
<a href="{{ url('/login/google') }}" class="btn btn-primary">login With google</a>
@endsection
