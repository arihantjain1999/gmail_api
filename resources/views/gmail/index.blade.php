@extends('layouts.app')
@section('content')
@php
    $labels =  getGmailList($loginDetails);

@endphp
    @foreach ($labels as $label)
        @foreach ($label as $label)
            <br><a href="#" value ='{{$label['id']}}' class="btn btn-success m-1">{{$label['name']}}</a>
        @endforeach
    @endforeach
@endsection 