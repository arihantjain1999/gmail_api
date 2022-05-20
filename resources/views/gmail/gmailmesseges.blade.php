@extends('layouts.app')
@section('content')
    @include('gmail.index')
    <div id="main">
        <div class="email-app mb-4">
            <main class="inbox">
                <div class="toolbar">
                    <div class="btn-group">
                        <button class="btn  " onclick="openNav()">
                            <i class="fa fa-bars fa-xl"></i>
                        </button>
                        {!! Form::open(['route' => 'label.scearch', 'method' => 'GET']) !!}
                        <div class="input-group">
                            <input type="search" class="form-control" placeholder="Search" name="scearch" />
                            <span class="input-group-text border-0" id="search-addon">
                                <button class="btn p-0" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </span>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="btn-group">
                        @php
                            $labels = DB::table('labels')
                                ->select('*')
                                ->get();
                        @endphp
                        {!! Form::open(['route' => 'label.create', 'method' => 'GET']) !!}
                        @csrf
                        <div class="btn w-60">
                            <select class="form-control" name='label_ids'>
                                <option value=''>None</option>
                                @foreach ($labels as $label)
                                    <div class="col-sm-5">
                                        <option value=' {{ $label->name }} '>
                                            {{ Str::ucfirst(Str::lower(str_replace('CATEGORY_', '', $label->name))) }}
                                        </option>
                                    </div>
                                @endforeach
                            </select>
                        </div>
                        {!! Form::submit('Sync', ['class' => 'btn btn-secondary  m-1 w-60']) !!}
                        {!! Form::close() !!}
                    </div>
                    {{-- @dd(empty($labelid)); --}}
                    <button type="button" class="btn">
                        <span class="fa fa-trash-o"></span>
                    </button>

                    @php
                        if (!empty($err)) {
                            echo $err;
                        }
                        if (empty($allmails)) {
                            $allmails = DB::table('mails')
                                ->select('*')
                                ->orderBy('history_id', 'desc')
                                ->whereNot('label_ids', 'like', '%TRASH%')
                                ->where('user_email', Auth::user()->email)
                                ->get();
                        }
                        
                    @endphp
                    @foreach ($allmails as $mail)
                        <ul class="messages">
                            <li class="message">
                                <a href="{{ route('label.show', $mail->mail_id) }}">
                                    <div>
                                        <div class="actions">

                                            {{-- <span class="action"><i class="fa fa-square-o"></i></span> --}}
                                            {{-- <span class="action"><i class="fa fa-star-o"></i></span> --}}
                                        </div>
                                        <div class="header">
                                            <span class="from"> <b> {{ $mail->from }} </b> &nbsp;&nbsp;
                                                {{ $mail->subject }}</span>
                                            <span class="date">

                                                {{-- <span class="fa fa-paper-clip"></span> --}}
                                                @php
                                                    
                                                    $date = dateFormat($mail->date);
                                                    echo $date;
                                                @endphp
                                            </span>


                                        </div>
                                        @php
                                        $maillabels = $mail->label_ids; 
                                           
                                        @endphp
                                        {{-- @if(empty($allmaillabels->label_ids)) --}}
                                        @if (Str::contains($maillabels, "STARRED"))
                                            <a href="{{ route('label.starredmail', ['delete' => $mail->mail_id]) }}"
                                                class="mx-1">
                                                <span class="fa fa-star"></span>
                                            </a>
                                        @else
                                            <a href="{{ route('label.starredmail', ['delete' => $mail->mail_id]) }}"
                                                class="mx-1">
                                                <span class="fa fa-star-o"></span>
                                            </a>
                                        @endif
                                        @if (!Str::contains($maillabels, "TRASH"))
                                        <a href="{{ route('label.deletemail', ['delete' => $mail->mail_id]) }}"
                                            class="mx-1">
                                            <span class="fa fa-trash-o"></span>
                                        </a>
                                        @endif
                                        {{-- @endif --}}
                                        {{-- <div class="title">
                                            {{ $mail->subject }}
                                        </div> --}}

                                        <div class="description">
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    @endforeach
                    {{-- @dd('hello'); --}}
                    <button class="open-button bg-white text-black  border-2  "  data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <i class="fa fa-pen mx-2"></i>
                        COMPOSE
                    </button>
                
                    <div class="modal" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered model-lg"><div class="modal-content">
                            <form action="{{ route('label.sendmail') }}" class="form-container" enctype="multipart/form-data" method="post">
                                @csrf
                                <button type="button" class="bg-danger text-white border-0" data-bs-dismiss="modal">&times;</button>
                            
                                <input class="fill-text my-2 p-2 w-100 border-0 rounded" type="text" placeholder="From" name="From" id="from" style="background-color: #f0f0f0" value="{{ Auth::user()->email }}">
                                <input class="fill-text my-2 p-2 w-100 border-0 rounded" type="text" placeholder="To" name="To" id="to" required style="background-color: #f0f0f0">
                                <input class="fill-text my-2 p-2 w-100 border-0 rounded" type="text" placeholder="Cc" name="Cc" id="cc" style="background-color: #f0f0f0">
                                <input class="fill-text my-2 p-2 w-100 border-0 rounded" type="text" placeholder="Bcc" name="Bcc" id="bcc" style="background-color: #f0f0f0">
                                <input class="fill-text my-2 p-2 w-100 border-0 rounded" type="text" placeholder="Subject" name="Subject" id="Subject" required style="background-color: #f0f0f0">
                                <textarea class="rounded" placeholder="Type message.." name="Body" id="messageText" required style="background-color: #f0f0f0"></textarea>
                                <br/>
                                <div class="d-flex justify-content-between">
                                    {{-- <div class="">
                                        <i class="fa fa-paperclip"></i>
                                        <input type="file" name="File">
                                    </div> --}}
                                    {{Form::file('image[]', ['multiple' => true , 'class' => 'fa fa-paperclip'])}}
                                    <button type="submit" class="btn rounded text-white" id="send" style="background-color: #32a89d;">Send <i class="fa fa-paper-plane"></i></button>
                                </div>
                            </form>
                        </div></div>
                    </div>
                </div>

                <script>
                    function openForm() {
                        document.getElementById("myForm").style.display = "block";
                    }

                    function closeForm() {
                        document.getElementById("myForm").style.display = "none";
                    }


                    /* Set the width of the side navigation to 250px and the left margin of the page content to 250px */
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


            </main>
        </div>
    @endsection
