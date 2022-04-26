<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Label;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('gmail.gmailmesseges');
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
        if (empty($fields)) {
            $emaildetais = ['emailId' => 'null', 'email' => $userDetails->email, 'user' => $userDetails->token, 'labelIds' => $label, 'labelname' => ''];
        } else {
            $labelName = last(array_keys($fields));

            $emaildetais = ['emailId' => 'null', 'email' => $userDetails->email, 'user' => $userDetails->token, 'labelIds' => $label, 'labelname' => $labelName];
            $response = getGmailMessage($emaildetais);

            if (is_string($response)) {
                return view('gmail.gmailmesseges', ['err' => $response]);

            } else {
                return view('gmail.gmailmesseges', ['labelid' => $label]);
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
        return view('gmail.gmailmesseges', ['labelid' => $label]);
    }

    public function sendmail(Request $request)
    {
        $mailDetails = $request->all();
         $emialFrom=$mailDetails['From'];
        $userDetails = Auth::User();

        if($userDetails->email == $emialFrom){
            $sentMessageData = sendGmailMessage($userDetails, $request->all());
            // $sentMessageID = $sentMessageData['id'];
            return view('gmail.gmailmesseges');
        }
        else{
            $err = '<div class="alert alert-danger" role="alert">
            You cannot send email with different emailID!
          </div>';
          return view('gmail.gmailmesseges' ,  ['err' => $err]);

        }
    }

        public function scearch(Request $request)
        {
             $scearchData= $request->all();
            //  dd($scearchData['scearch']);
            // $allmails = DB::table('mails')
            //                     ->select('*')
            //                     ->where('from', 'like', '%' . $scearchData . '%')
            //                     ->where('user_email', Auth::user()->email)
            //                     ->get();
            return view('gmail.gmailmesseges' , ['scearchData' => $scearchData['scearch'] ]);
        }
}
