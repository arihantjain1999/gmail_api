@extends('layouts.app')
@section('content')
    {!!Form::open(['route' => 'label.index'] , ['method'=>'POST'])!!}

    <select class="form-control" name="Contact_detach_id " >  
        @foreach($labels as $label) 
        <option value= "{{ $label->id }}"> {{$label->name}}</option>
        @endforeach
    </select>   
    {!!Form::submit('hit' , ['class'=>'btn btn-primary ml-3']);!!}
    {!! Form::close() !!}
@endsection 
