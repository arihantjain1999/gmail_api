<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allmails = DB::table('mails')
            ->select('*')
            ->orderBy('id', 'desc')
            ->whereNot('label_ids' , 'like' ,'%TRASH%')
            ->where('user_email', Auth::user()->email)
            ->get();
        return view('gmail.gmailmesseges', ['allmails' => $allmails]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $userDetails = Auth::user();
        $fields = $request->all();
        // dd($fields);
        $label = last($fields);
        // dd($label);
        if (empty($fields)) {
            $emaildetais = ['emailId' => 'null', 'email' => $userDetails->email, 'user' => $userDetails->token, 'labelIds' => $label, 'labelname' => ''];
        } else {
            $labelName = last(array_keys($fields));

            $emaildetais = ['emailId' => 'null', 'email' => $userDetails->email, 'user' => $userDetails->token, 'labelIds' => $label, 'labelname' => $labelName];
            $response = getGmailMessage($emaildetais);

            if (is_string($response)) {
                return view('gmail.gmailmesseges', ['err' => $response]);
            } else {
                $allmails = DB::table('mails')
                    ->select('*')
                    ->where('label_ids', 'like', '%' . $label . '%')
                    ->whereNot('label_ids' , 'like' ,'%TRASH%')
                    ->where('user_email', Auth::user()->email)
                    ->get();
                return view('gmail.gmailmesseges', ['allmails' => $allmails]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return view('gmail.singleemail', ['mail_id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function edit(Label $label)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Label $label)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        //
    }

    public function getMyLabels(Request $request)
    {
        $label = $request->all();
        $label = last($label);
        // dd($label);
        if($label!='TRASH'){

            $allmails = DB::table('mails')
            ->select('*')
            ->where('label_ids', 'like', '%' . $label . '%')
            ->whereNot('label_ids' , 'like' ,'%TRASH%')
            ->where('user_email', Auth::user()->email)
            ->get();
        }
        else{
            $allmails = DB::table('mails')
            ->select('*')
            ->where('label_ids', 'like', '%' . $label . '%')
            // ->whereNot('label_ids' , 'like' ,'%TRASH%')
            ->where('user_email', Auth::user()->email)
            ->get();

        }
        return view('gmail.gmailmesseges', ['allmails' => $allmails]);
    }

    public function sendmail(Request $request)
    {
        $mailDetails = $request->all();
        $emialFrom = $mailDetails['From'];
        $userDetails = Auth::User();

        if ($userDetails->email == $emialFrom) {
            $sentMessageData = sendGmailMessage($userDetails, $request);
            // $sentMessageID = $sentMessageData['id'];
            return view('gmail.gmailmesseges');
        } else {
            $err = '<div class="alert alert-danger" role="alert">
            You cannot send email with different emailID!
          </div>';
            return view('gmail.gmailmesseges', ['err' => $err]);

        }
    }

    public function scearch(Request $request)
    {
        $scearchData = $request->all();
        //  dd($scearchData['scearch']);
        $allmails = DB::table('mails')
            ->select('*')
            ->orwhere('from', 'like', '%' . $scearchData['scearch'] . '%')
            // ->orwhere('label_ids', 'like', '%' . $scearchData['scearch'] . '%')
            ->orwhere('to', 'like', '%' . $scearchData['scearch'] . '%')
            ->orwhere('subject', 'like', '%' . $scearchData['scearch'] . '%')
            ->where('user_email', Auth::user()->email)
            ->get();
        return view('gmail.gmailmesseges', ['allmails' => $allmails]);
    }

    public function deletemail(Request $request)
    {
        $deletemail = $request->all();
        $allmails = DB::table('mails')
            ->select('mail_id', 'label_ids')
            ->where('mail_id', $deletemail['delete'])
            ->where('user_email', Auth::user()->email)
            ->first();
        $adddeletelabel = $allmails->label_ids . ',TRASH';
        DB::table('mails')
            ->where('mail_id', $deletemail['delete'])
            ->where('user_email', Auth::user()->email) // find your user by their email
            ->limit(1) // optional - to ensure only one record is updated.
            ->update(array('label_ids' => $adddeletelabel)); // update the record in the DB.
        return view('gmail.gmailmesseges');
    }
    public function starredmail(Request $request)
    {
        $deletemail = $request->all();
        $allmails = DB::table('mails')
            ->select('mail_id', 'label_ids')
            ->where('mail_id', $deletemail['delete'])
            ->where('user_email', Auth::user()->email)
            ->first();
        $adddeletelabel = $allmails->label_ids . ',STARRED';
        DB::table('mails')
            ->where('mail_id', $deletemail['delete'])
            ->where('user_email', Auth::user()->email) // find your user by their email
            ->limit(1) // optional - to ensure only one record is updated.
            ->update(array('label_ids' => $adddeletelabel)); // update the record in the DB.
        return view('gmail.gmailmesseges');
    }
    public function showUser(Request $request)
    {
        // dd($request->all());
        $userData = $request->all();

        $users = DB::table('users')->select('*')->where('email', $userData['email'])->first();
        // dd($users);
        // $users = User::find($user->id);
        // dd($user);

        // $decrypted = Crypt::decrypt($user->password);
        return view('gmail.label',compact('users'));
    }
}

