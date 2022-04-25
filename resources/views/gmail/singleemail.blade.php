@extends('layouts.app')
@section('content')
    @include('gmail.index')
    <div id="main">
        @php
            $maildetails = $user = DB::table('mails')
                ->where('mail_id', $mail_id)
                ->first();
            // dump($maildetails->body);
            // $encodedData = str_replace(' ','+',$maildetails->body);
            $decocedData = base64_decode($maildetails->body);
        @endphp
        <div class="m-">
            <div class="row inbox-wrapper">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-9 email-content">
                                    <div class="email-head">
                                        <div class="email-head-subject">
                                            <div class="btn-group my-3">
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
                                            </div>

                                            <div class="title d-flex align-items-center justify-content-between">

                                                <div class="d-flex align-items-center">

                                                    <h3>Mail</h3>
                                                </div>
                                                {{-- <div class="icons">
                          <a href="#" class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share text-muted hover-primary-muted" data-toggle="tooltip" title="" data-original-title="Forward"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></a>
                          <a href="#" class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer text-muted" data-toggle="tooltip" title="" data-original-title="Print"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a>
                          <a href="#" class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash text-muted" data-toggle="tooltip" title="" data-original-title="Delete"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                          </a>
                        </div> --}}
                                            </div>
                                        </div>
                                        <div
                                            class="email-head-sender d-flex align-items-center justify-content-between flex-wrap">
                                            <div class="d-flex align-items-center">

                                                <div class="sender align-items-center">
                                                    <div> From : {{ $maildetails->from }}</div>
                                                    <div>To : {{ $maildetails->to }}</div>
                                                    <div class="date">{{ $maildetails->date }}</div>
                                                    <div>Subject : {{ $maildetails->subject }}</div>
                                                    {{-- <div class="actions dropdown">
                            <a class="icon" href="#" data-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></a>
                            <div class="dropdown-menu" role="menu">
                              <a class="dropdown-item" href="#">Mark as read</a>
                              <a class="dropdown-item" href="#">Mark as unread</a>
                              <a class="dropdown-item" href="#">Spam</a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item text-danger" href="#">Delete</a>
                            </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="email-body">
                                    @php
                                        echo $decocedData;
                                    @endphp
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
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
@endsection
