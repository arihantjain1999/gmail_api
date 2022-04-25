<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <nav class="navbar navbar-expand-md shadow-sm align-items-start">
        <ul class="nav ">
            <li class="nav-item">
                @php
                    $labels = DB::table('labels')
                        ->select('*')
                        ->get();
                @endphp
                @foreach ($labels as $label)
                    {!! Form::open(['route' => 'label.get-labels', 'method' => 'GET']) !!}
                    @csrf
                    <div class="form-group d-none">
                        <div class="col-sm-5">
                            <select class="form-control" name={{ $label->name }}>
                                <option value={{ $label->id_ }}>None</option>
                            </select>
                        </div>
                    </div>
                    {!! Form::submit(Str::ucfirst(Str::lower($label->name)), ['class' => 'btn btn-dark  m-1 w-100']) !!}
                    {!! Form::close() !!}
                @endforeach
            </li>
        </ul>
    </nav>
</div>
