@extends('layouts.app')
@section('content')

<div class="w-50 ml-0 mr-0 mx-auto text-center">
    <img src="https://cdn-icons-png.flaticon.com/512/1225/1225801.png?w=700">
    <h1 id="typing"></h1>
    <a href="{{ url('/login/google') }}" class="btn text-white" style="background-color: #32a89d;">LOGIN WITH GOOGLE</a>
</div>

    <script>
        var i = 0;
        var txt = 'WELCOME TO MAIL APP...';
        var speed = 100;
        
        function typeWriter() {
            if (i < txt.length) {
                document.getElementById("typing").innerHTML += txt.charAt(i);
                i++;
                setTimeout(typeWriter, speed);
            }
        }
        typeWriter();
    </script>
@endsection