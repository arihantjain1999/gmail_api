


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
        </div>
        <button type="button" class="btn btn-light">
            <span class="fa fa-trash-o"></span>
        </button>
    {{-- </div>
    {{$label}}
    {{-- @dd($label);    --}}
    {{-- @dd(empty($label)); --}} 
    @php   
        // if(empty($label)){

        //     $allmails = DB::table('mails')->select('*')->where('label_ids', 'LIKE', '%'.$label.'%')->get();
        // } 
        // else{
            $allmails = DB::table('mails')->select('*')->get();
        // }
    @endphp
     @foreach ($allmails as $mail) 
        {{-- @dd($mail); --}}
        <ul class="messages">
            <li class="message unread">
                <a href="#">
                    <div class="actions">
                        <span class="action"><i class="fa fa-square-o"></i></span>
                        <span class="action"><i class="fa fa-star-o"></i></span>
                    </div>
                    <div class="header">
                        <span class="from">{{$mail->from}}</span>
                        <span class="date">
                        <span class="fa fa-paper-clip"></span>{{$mail->date}}</span>
                    </div>
                    <div class="title">
                        {{$mail->subject}}
                    </div>
                    <div class="description">
                        {{-- {{$mail->body}} --}}
                    </div>
                </a>
            </li>
                </ul>
      
    @endforeach

</main>
</div>