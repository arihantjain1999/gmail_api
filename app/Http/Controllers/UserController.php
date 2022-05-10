<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        // dd($user);
        $users = User::all();
        return view('gmail.users' , compact('users'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd('hellpo');
        return view('gmail.create');
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // $request->validate([
        //     'name' => 'required',
        //     'email' => 'required',
        // ]);
        $fields = $request->all();
        // dd($fields);
        $password = $fields['password'];
        $c_password = $fields['c_password'];
        if($password == $c_password){
            $password = bcrypt($password);
            $fields['password'] = $password;
            $user = User::create($fields);
            return redirect()->route('user.index');
        }
        else{
            return redirect()->route('user.create' , ['err' => "password does not match"] );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        // dd($user);
        $users =DB::table('users')
        ->select('*')
        ->where('email', $user)
        ->first();
        // $decrypted = Crypt::decrypt($user->password);
        return view('gmail.label',compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // dd($user->email);
        $user =DB::table('users')
        ->select('*')
        ->where('email', $user->email)
        ->first();
        // dd($user);   
        return view('gmail.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $fields = $request->all();
        $users = User::find($user->id);
        $users->update($fields);
        // $users = User::all();    
        $updateuseremail = $request->all() ;
        $users = $updateuseremail['email'];
        $users =DB::table('users')
        ->select('*')
        ->where('email', $users)
        ->first();
        return view('gmail.label',compact('users'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        User::find($user->id)->delete();
        return view('auth.login');
    }
}
