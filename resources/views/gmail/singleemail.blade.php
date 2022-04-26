@extends('layouts.app')
@section('content')
    @include('gmail.index')
    <div id="main">
        @php
            $maildetails = $user = DB::table('mails')
                ->where('mail_id', $mail_id)
                ->first();
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
                                                    <a href="{{route('label.index')}}" class="btn btn-dark">Back</a>
                                            </div>
                                            <div class="title d-flex align-items-center justify-content-between">

                                                <div class="d-flex align-items-center">

                                                    <h3>Mail</h3>
                                                </div>
                                                
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
