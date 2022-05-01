<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <nav class="navbar navbar-expand-md shadow-sm align-items-start">
        <ul class="nav ">
            <li class="nav-item">
                @php
                    $labels = DB::table('labels')
                        ->select('*')
                        ->whereNot('id_', 'like', '%CHAT%')
                        ->whereNot('id_', 'like', '%CATEGORY_FORUMS%')
                        ->whereNot('id_', 'like', '%UNREAD%')
                        ->whereNot('id_', 'like', '%Label_3%')
                        ->whereNot('id_', 'like', '%Label_4%')
                        ->whereNot('id_', 'like', '%Label_5%')
                        // ->whereNot('id_' , 'like' , '%CATEGORY_PERSONAL%')
                        ->get();
                    // dd($labels);
                @endphp
                @foreach ($labels as $label)
                    {!! Form::open(['route' => 'label.get-labels', 'method' => 'GET']) !!}
                    @csrf
                    @if ($label->name != 'CHAT')
                        {{-- @dump($label->name); --}}
                        <div class="form-group d-none">
                            <div class="col-sm-5">
                                <select class="form-control" name={{ $label->name }}>
                                    <option value={{ $label->id_ }}>None</option>
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="d-flex m-2 align-items-center">
                        <i class="fa fa-{{ Str::lower($label->name) }}"></i>
                        @if ($label->name == 'STARRED')
                            <i class="fa fa-star"></i>
                        @elseif ($label->name == 'DRAFT')
                            <i class="fa fa-file"></i>
                        @elseif ($label->name == 'SPAM')
                            <i class="fa fa-exclamation"></i>
                        @elseif($label->name == 'SENT')
                            <i class="fa fa-paper-plane"></i>
                        @elseif($label->name == 'CATEGORY_PERSONAL')
                            <i class="fa fa-check"></i>
                        @elseif($label->name == 'CATEGORY_SOCIAL')
                            <i class="fa fa-tag"></i>
                        @elseif($label->name == 'CATEGORY_PROMOTIONS')
                            <i class="fa fa-tag"></i>
                        @elseif($label->name == 'CATEGORY_UPDATES')
                            <i class="fa fa-tag"></i>
                        @elseif($label->name == 'IMPORTANT')
                            <i class="fa fa-tag"></i>
                        @endif

                        {!! Form::submit(Str::ucfirst(Str::lower(str_replace('CATEGORY_', '', $label->name))), ['class' => 'border-0 rounded mx-2 nav-link w-100  text-dark text-start dropdown-item list-group-item label-name', 'id' => 'navbutton']) !!}
                    </div>
                    {!! Form::close() !!}
                @endforeach
                {{-- @dd('helll'); --}}
            </li>
        </ul>
    </nav>
</div>
