<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use App\Models\Meeting;
use App\Models\MeetingMember;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware("admin")->except(["teacherNearestMeeting"]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $meetings = Meeting::all();

        return view('admin.meetings.index')
            ->with([
                "meetings" => $meetings
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $languages = Language::all();
        $teachers = User::teachers();

        return view('admin.meetings.create')
            ->with([
                "languages" => $languages,
                "teachers" => $teachers
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $rules = [
            'day' => "required",
            'start' => "required",
            'end' => "required",
            'type' => "required",
        ];

        if (isset($request->type) and intval($request->type) == 2) {
            $rules["language"] = "required";
        } elseif (isset($request->type) and intval($request->type) == 3) {
            $rules["teacher"] = "required";
        }

        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return redirect()->back()->with(['message' => __('messages.err_form_not_filled_correctly'), 'msg_type' => "danger"]);
        }

        $meeting = new Meeting();

        $meeting->day = Carbon::createFromFormat("d/m/Y", $request->day);
        $meeting->start = Carbon::createFromFormat("d/m/Y H:i", $request->day . " " . $request->start);
        $meeting->end = Carbon::createFromFormat("d/m/Y H:i", $request->day . " " . $request->end);
        $meeting->comment = $request->comment;
        $meeting->type = $request->type;
        if ($meeting->type == 2) {
            $meeting->language_id = $request->language;
        }

        $meeting->save();

        $teachers = null;
        if ($meeting->type == 1) {
            $teachers = User::teachers();

        } elseif ($meeting->type == 2) {
            $lang = Language::find($request->language);
            $teachers = $lang->teachers;
        } elseif ($meeting->type == 3) {
            $teachers = User::whereIn("id", $request->teacher)->get();
        } else $teachers = [];

        foreach ($teachers as $t) {
            $mm = new MeetingMember();
            $mm->user_id = $t->id;
            $mm->meeting_id = $meeting->id;
            $mm->save();
        }

        return redirect()
            ->route('admin.meetings.index')
            ->with(['message' => __('meeting.meeting_created'), 'msg_type' => "success"]);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        dd('Show: nyi');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        dd('Edit: nyi');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        dd('Update: nyi');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $meeting = Meeting::findOrFail($id);
        $members = MeetingMember::where('meeting_id', $meeting->id)->pluck('id');

        MeetingMember::destroy($members);
        Meeting::destroy($id);

        return redirect()->route('admin.meetings.index')
            ->with([
               'message' => "Zmazané",
               'msg_type' => "success"
            ]);
    }

    public function teacherNearestMeeting($mid){
        $user = Auth::user();

        if(!$user->hasRole('teacher')){
            return redirect()->back()
                ->with([
                    'message' => __('messages.access_denied')." C:1",
                    'msg_type' => "danger"
                ]);
        }

        $tnm = Meeting::teachersNearestMeeting($user->id);

        if($tnm and $tnm->id != $mid){
            return redirect()->back()
                ->with([
                    'message' => __('messages.access_denied')." C:2",
                    'msg_type' => "danger"
                ]);
        }

        $meeting = Meeting::find($mid);

        return view('admin.meetings.show_teacher')
            ->with([
                "meeting" => $meeting,
            ]);
    }
}
