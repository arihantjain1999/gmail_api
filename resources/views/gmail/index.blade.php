@extends('layouts.app')
@section('content')
        <div class="email-app mb-4">
            <nav class="navbar navbar-expand-md shadow-sm align-items-start">
                <ul class="nav ">
                    <a href="/compose" class="btn btn-danger btn-block w-100">Compose</a>
                    <li class="nav-item">
                        {{-- @dd($labels); --}}
                        @php
                            $labels = DB::table('labels')->select('*')->get();
                        @endphp
                        {{-- @dd($labels); --}}
                        @foreach($labels as $label)
                        {{-- @dump($label); --}}
                            {!!Form::open(['route' => 'label.create' , 'method'=>'GET'])!!}
                             @csrf
                            <div class="form-group d-none">
                                <div class="col-sm-5">
                                        <select class="form-control" name={{$label->name}}>  
                                            <option value={{$label->id_}}>None</option>
                                        </select>
                                </div>
                             </div>
                             {{-- {{$str = Str::ucfirst($label->name)}} --}}
                                {!! Form::submit( Str::ucfirst(Str::lower($label->name)) , ['class' => 'nav-link m-1 w-100']) !!}    
                            {!! Form::close() !!}
                        @endforeach
                        {{-- @dd('hell'); --}}
                    </li>
                </ul>
            </nav>
            @include('gmail.gmailmesseges')
            <button class="open-button bg-white text-black rounded-pill border border-success border-2" onclick="openForm()">
                <i class="fa fa-pen mx-2"></i>
                COMPOSE
            </button>
        
            <div class="chat-popup" id="myForm">
            <form action="/action_page.php" class="form-container">
                
                <input class="fill-text my-2 p-2 w-100 border-0 bg-light" type="text" placeholder="From" name="From" required>
                <input class="fill-text my-2 p-2 w-100 border-0 bg-light" type="text" placeholder="To" name="To" required>
                <input class="fill-text my-2 p-2 w-100 border-0 bg-light" type="text" placeholder="Subject" name="Subject" required>
                <textarea class="bg-light" placeholder="Type message.." name="msg" required></textarea>
        
                <div class="d-flex">
                    <i class="fa fa-paperclip"></i>
                </div>
                <br/>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn rounded btn-success text-white">Send</button>
                    <button type="button" class="btn cancel rounded-pill text-red border border-danger" onclick="closeForm()">Back</button>
                </div>
            </form>
            </div>
        
            <script>
                function openForm() {
                    document.getElementById("myForm").style.display = "block";
                }
        
                function closeForm() {
                    document.getElementById("myForm").style.display = "none";
                }
            </script>
@endsection