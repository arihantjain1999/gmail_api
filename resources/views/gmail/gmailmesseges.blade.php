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
                        {{-- <button type="button" class="btn  ">
                            <span class="fa fa-envelope"></span>
                        </button>
                        <button type="button" class="btn  ">
                            <span class="fa fa-star"></span>
                        </button>
                        <button type="button" class="btn  ">
                            <span class="fa fa-star-o"></span>
                        </button>
                        <button type="button" class="btn  ">
                            <span class="fa fa-bookmark-o"></span>
                        </button> --}}
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
                    <button type="button" class="btn  ">
                        <span class="fa fa-trash-o"></span>
                    </button>

                    @php
                        if (!empty($err)) {
                            echo $err;
                        }
                        if (empty($allmails)) {
                            $allmails = DB::table('mails')
                                ->select('*')
                                ->orderBy('id', 'desc')
                                ->whereNot('label_ids' , 'like' ,'%TRASH%')
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

                                                <span class="fa fa-paper-clip"></span>
                                                @php
                                                    
                                                    $date = dateFormat($mail->date);
                                                    echo $date;
                                                @endphp
                                            </span>

                                            <a href="{{ route('label.deletemail', ['delete' => $mail->mail_id]) }}"
                                                class="mx-1">
                                                <span class="fa fa-trash-o"></span>
                                            </a>
                                            <a href="{{ route('label.starredmail', ['delete' => $mail->mail_id]) }}"
                                                class="mx-1">
                                                <span class="fa fa-star"></span>
                                            </a>
                                        </div>
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
                    <button class="open-button bg-white text-black  border-2" onclick="openForm()">
                        <i class="fa fa-pen mx-2"></i>
                        COMPOSE
                    </button>

                    <div class="chat-popup" id="myForm">
                        <form action=" {{ route('label.sendmail') }} " class="form-container">
                            {{-- <div class="form-row mb-3">
                                <label for="to" class="col-2 col-sm-1 col-form-label">To:</label>
                                <div class="col-10 col-sm-11">
                                    <input type="email" class="form-control" id="to" placeholder="Type email">
                                </div>
                            </div> --}}
                            <label>From : </label>
                            <input class="fill-text my-2 p-2 w-100 border-0 bg-light" type="text" placeholder="From"
                                name="From" value="{{ Auth::user()->email }}" required>
                            <label>To : </label>
                            <input class="fill-text my-2 p-2 w-100 border-0 bg-light" type="text" placeholder="To" name="To"
                                required>
                            <label>Subject : </label>
                            <input class="fill-text my-2 p-2 w-100 border-0 bg-light" type="text" placeholder="Subject"
                                name="Subject" required>
                            <label>Type Messege : </label>
                            <textarea class="bg-light" placeholder="Type message.." name="Body" required></textarea>
                            <div class="d-flex">
                                <i class="fa fa-paperclip"></i>
                            </div>
                            <br />
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn rounded btn-success text-white">Send</button>
                                <button type="button" class="btn cancel btn-danger" onclick="closeForm()">Back</button>
                            </div>
                        </form>
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
                        document.getElementById("mySidenav").style.width = "250px";
                        document.getElementById("main").style.marginLeft = "250px";
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
