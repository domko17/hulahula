<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\ClassFeedback;
use App\Models\EmailMessage;
use App\Models\User\Student;
use App\Models\User\Teacher;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $feedbacks = ClassFeedback::all();

        return view('feedback.index')
            ->with([
                'feedbacks' => $feedbacks
            ]);
    }

    /**
     * @param $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function indexStudent($id)
    {
        /** @var Student $student */
        $student = Student::findOrFail($id);

        if (!($id == Auth::id() or User::find($id)->hasRole('admin'))) {
            return redirect()->back()->with([
                'message' => 'Unauthorized',
                'msg_type' => 'danger'
            ]);
        }

        $can = $student->canDoFeedbackGetAll();

        $feedbacks = ClassFeedback::where('student_id', $id)->get();

        return view('feedback.index_student')
            ->with([
                'feedbacks' => $feedbacks,
                'new_feedbacks' => $can,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'teacher_id' => 'required',
            'student_id' => 'required',
        ];

        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->with('message', __('messages.err_lecture_info_not_edited'))
                ->with('msg_type', 'danger');
        }

        $data = array();
        foreach ($request->request as $k => $v) {
            if (strpos($k, "feedback_answer") !== false) {
                $data[$k] = $v;
            }
        }

        $feedback = new ClassFeedback();
        $feedback->teacher_id = $request->teacher_id;
        $feedback->student_id = $request->student_id;
        $feedback->answers = json_encode($data);
        $feedback->save();

        {   // Notifikacia o zapisani studenta
            // declare
            $recipients = [];
            $subject = __('email.new_teacher_feedback_added');
            $module = "feedback_added";
            $data = [];

            //$recipients[] = User::find(1)->email;
            $recipients[] = User::find(4)->email;

            $data["feedback"] = $feedback->id;
            $data["student"] = $feedback->student_id;
            $data["teacher"] = $feedback->teacher_id;

            // vytvorenie mailu
            EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
        }


        return redirect()->route('dashboard')->with([
            'feedback_created' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        /** @var ClassFeedback $feedback */
        $feedback = ClassFeedback::findOrFail($id);

        $data = json_decode($feedback->answers);

        return view('feedback.show')
            ->with([
                'data' => $data,
                'teacher' => $feedback->teacher,
                'student' => $feedback->student,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|RedirectResponse|Response|View
     */
    public function edit($id)
    {
        $model = ClassFeedback::findOrFail($id);

        if (!($model->student_id == Auth::id() or User::find($id)->hasRole('admin'))) {
            return redirect()->back()->with([
                'message' => 'Unauthorized',
                'msg_type' => 'danger'
            ]);
        }
        $data = json_decode($model->answers);
        return view('feedback.edit_feedback')->with([
            'data' => $data,
            'model' => $model,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function update(Request $request, $id)
    {
        $data = array();
        foreach ($request->request as $k => $v) {
            if (strpos($k, "feedback_answer") !== false) {
                $data[$k] = $v;
            }
        }

        $feedback = ClassFeedback::findOrFail($id);

        if (!($feedback->student_id == Auth::id() or User::find($id)->hasRole('admin'))) {
            return redirect()->back()->with([
                'message' => 'Unauthorized',
                'msg_type' => 'danger'
            ]);
        }

        $feedback->answers = json_encode($data);
        $feedback->save();

        {   // Notifikacia o zapisani studenta
            // declare
            $recipients = [];
            $subject = __('email.teacher_feedback_updated');
            $module = "feedback_added";
            $data = [];

            //$recipients[] = User::find(1)->email;
            $recipients[] = User::find(4)->email;

            $data["feedback"] = $feedback->id;
            $data["student"] = $feedback->student_id;
            $data["teacher"] = $feedback->teacher_id;

            // vytvorenie mailu
            EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
        }


        return redirect()->back()->with([
            'message' => "Success",
            'msg_type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function createFeedback(Request $request, $teacher_id)
    {
        $teacher = Teacher::findOrFail($teacher_id);
        return view('feedback.create_feedback')
            ->with([
                'teacher' => $teacher
            ]);
    }
}
