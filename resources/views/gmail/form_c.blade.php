<div class="row mx-5 px-5" id="container">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group row">
         <strong >First Name</strong>   
        <div class="col-sm-5">
                {!! Form::text('name', $user->name??'' , ['placeholder' => ' Name' , 'class'=>'form-control']); !!}
        </div>
            @error('name')
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $message }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @enderror    

        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Email:</strong>
            <div class="col-sm-5">
                {!!Form::text('email', $user->email??'', ['placeholder' => 'abc@gmail.com' , 'class'=>'form-control']); !!}
            </div>
            @error('email')
                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
    @php
        $useremail = Auth::user()->email;
        $users = DB::table('users')
        ->select('user_type')
        ->where('email', $useremail)
        ->first();
        // dd($users->user_type);
    @endphp
    @if ($users->user_type == 'Admin')
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>User Type:</strong>
            <div class="col-sm-5">
                <select name="user_type">  
                    <option value="Admin">Admin</option>
                    <option value="">User</option>
                </select>
            </div>
        </div>
    </div>
    @endif
    <div class="col-xs-12 col-sm-12 col-md-12 my-2">
       
        {{-- {!!Form::submit('Create Contact' , ['class'=>'btn btn-primary ml-3']);!!} --}}
    </div>
</div>