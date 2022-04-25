@extends('layouts.app')
@section('content')
    @include('gmail.index')
    <div id="main">
        <div class="email-app mb-4">
            <main class="inbox">
                <div class="toolbar">
                    <div class="btn-group">
                        <button class="btn btn-light" onclick="openNav()">
                            <i class="fa fa-bars fa-xl"></i>
                        </button>
                        <button type="button" class="btn btn-light">
                            <span class="fa fa-envelope"></span>
                        </button>
                        <button type="button" class="btn btn-light">
                            <span class="fa fa-star"></span>
                        </button>
                        <button type="button" class="btn btn-light">
                            <span class="fa fa-star-o"></span>
                        </button>
                        <button type="button" class="btn btn-light">
                            <span class="fa fa-bookmark-o"></span>
                        </button>

                        {{-- <div>
                        <a href="{{ route('label.create') }}" name = 'label_ids' value="abc">sync</a>
                    </div> --}}
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-light">
                            <span class="fa fa-mail-reply"></span>
                        </button>
                        <button type="button" class="btn btn-light">
                            <span class="fa fa-mail-reply-all"></span>
                        </button>
                        <button type="button" class="btn btn-light">
                            <span class="fa fa-mail-forward"></span>
                        </button>
                        {!! Form::open(['route' => 'label.create', 'method' => 'GET']) !!}
                        @csrf
                        <div class="form-group d-none">
                            <div class="col-sm-5">
                                <select class="form-control" name='label_ids'>
                                    <option value=''>None</option>
                                </select>
                            </div>
                        </div>
                        {!! Form::submit('Sync', ['class' => 'btn btn-secondary  m-1 w-100']) !!}
                        {!! Form::close() !!}
                    </div>
                    {{-- @dd(empty($labelid)); --}}
                    <button type="button" class="btn btn-light">
                        <span class="fa fa-trash-o"></span>
                    </button>
                    @php
                        if (!empty($err)) {
                            echo $err;
                        }
                        if (empty($labelid)) {
                            $allmails = DB::table('mails')
                                ->select('*')
                                ->orderBy('id', 'desc')
                                ->where('user_email', Auth::user()->email)
                                ->get();
                        } else {
                            $allmails = DB::table('mails')
                                ->select('*')
                                ->where('label_ids', 'like', '%' . $labelid . '%')
                                ->where('user_email', Auth::user()->email)
                                ->get();
                        }
                    @endphp
                    @foreach ($allmails as $mail)
                        <ul class="messages">
                            <li class="message unread">
                                <a href="{{ route('label.show', $mail->mail_id) }}">
                                    <div>
                                        <div class="actions">
                                            <span class="action"><i class="fa fa-square-o"></i></span>
                                            <span class="action"><i class="fa fa-star-o"></i></span>
                                        </div>
                                        <div class="header">
                                            <span class="from">{{ $mail->from }}</span>
                                            <span class="date">
                                                <span class="fa fa-paper-clip"></span>{{ $mail->date }}</span>
                                        </div>
                                        <div class="title">
                                            {{ $mail->subject }}
                                        </div>
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
                        <form action="/action_page.php" class="form-container">

                            <input class="fill-text my-2 p-2 w-100 border-0 bg-light" type="text" placeholder="From"
                                name="From" required>
                            <input class="fill-text my-2 p-2 w-100 border-0 bg-light" type="text" placeholder="To" name="To"
                                required>
                            <input class="fill-text my-2 p-2 w-100 border-0 bg-light" type="text" placeholder="Subject"
                                name="Subject" required>
                            <textarea class="bg-light" placeholder="Type message.." name="msg" required></textarea>

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
