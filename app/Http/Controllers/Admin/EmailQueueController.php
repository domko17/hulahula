<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmailMessage;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class EmailQueueController extends Controller
{

    public function __construct()
    {
        $this->middleware(["admin"]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emails = EmailMessage::orderByDesc('send_time')->get();

        return view('admin.email.listing')->with('emails', $emails);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        EmailMessage::destroy($id);

        return redirect()->back()->with('message', "Email deleted from the queue")->with('msg', 'success');
    }

    public function sendOne($id)
    {
        $res = EmailMessage::sendOne($id);
        if ($res) {
            return redirect()->back()->with("message", "OK")->with('msg_type', "success");
        }
        return redirect()->back()->with("message", "Error")->with('msg_type', "danger");
    }

    public function renderMail($id)
    {
        $em = EmailMessage::find($id);

        if (!$em) {
            return view('email.error_preview');
        }

        $template = $em->getTemplate($em);

        $data_raw = json_decode($em->data);
        $content = $data_raw;

        $emails = json_decode($em->recipients);
        if (!is_array($emails)) $emails = [$emails];
        //get locale function (from recipients) and add it to content
        $lang = "sk";
        $u = User::where('email', $emails[0])->first();
        if ($u) {
            $lang = $u->profile->locale;
        }
        $content->lang = $lang;

         dump($template);
         dump($data_raw);
         //dd($content);

        return view('email.html.' . $template)
            ->with('content', $content)
            ->with('preview', true)
            ->with('message', $em);
    }
}
