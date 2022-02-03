<?php

namespace App\Http\Controllers\Admin;

use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionTranslation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Console\Question\Question;

class SurveyController extends Controller
{
    public function __construct()
    {
        $this->middleware(["admin"])
            ->except([]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $questions = SurveyQuestion::all();

        return view('survey.index')
            ->with([
                "questions" => $questions
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('survey.create')
            ->with([

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
            "question" => "required",
            "type" => "required",
            "visible" => "required",
        ];

        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->with(["message" => __('messages.err_form_not_filled_correctly'), 'msg_type' => "danger"]);
        }

        $q = new SurveyQuestion();

        $q->question = $request->question;
        $q->type = $request->type;

        if ($request->visible == 1) {
            $q->students = 1;
            $q->teachers = 1;
        } elseif ($request->visible == 2) {
            $q->students = 1;
        } elseif ($request->visible == 3) {
            $q->teachers = 1;
        }

        $q->save();

        return redirect()->route('survey.index')
            ->with([
                "message" => "OK", 'msg_type' => "success"
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $question = SurveyQuestion::findOrFail($id);
        $answers = $question->answers;

        return view('survey.show')
            ->with([
               "question" => $question,
               "answers" => $answers,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        dd("edit - NYI");
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
        dd("update - NYI");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $q = SurveyQuestion::find($id);
        $qa = $q->answers()->pluck('id');
        $qt = $q->translations()->pluck('id');

        SurveyAnswer::destroy($qa);
        SurveyQuestionTranslation::destroy($qt);
        SurveyQuestion::destroy($id);

        return redirect()->route('survey.index')
            ->with([
                "message" => "OK", 'msg_type' => "success"
            ]);
    }
}
