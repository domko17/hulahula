<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LanguagesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(
            ["admin"],
            ['only' =>
                [
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy'
                ]
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = Language::all();

        return view('admin.languages.listing')->with("languages", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.languages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->_validation_rules());

        $language = new Language();

        $language->name_native = $request->name_native;
        $language->name_en = $request->name_en;
        $language->name_sk = $request->name_sk;
        $language->abbr = $request->abbr;
        $language->icon = $request->icon;
        $language->description = $request->description;

        $language->save();

        return redirect()
            ->route('admin.languages.index')
            ->with(["message" => __('alert.create_success'), "msg_type" => "success"]);

    }

    private function _validation_rules()
    {
        return [
            "name_native" => "required",
            "name_en" => "required",
            "name_sk" => "required",
            "abbr" => "required",
            "icon" => "required",
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $language = Language::findOrFail($id);
        $lectors = $language->teachers;
        $classes_i = $language->classes_i_future()->limit(3)->get();
        $classes_c = $language->classes_c_future()->limit(3)->get();

        return view('admin.languages.show')
            ->with('language', $language)
            ->with('lectors', $lectors)
            ->with('nearest_i', $classes_i)
            ->with('nearest_c', $classes_c);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Language::findOrFail($id);

        return view('admin.languages.edit')
            ->with("data", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, $this->_validation_rules());

        $language = Language::findOrFail($id);

        $language->name_native = $request->name_native;
        $language->name_en = $request->name_en;
        $language->name_sk = $request->name_sk;
        $language->abbr = $request->abbr;
        $language->icon = $request->icon;
        $language->description = $request->description;

        $language->save();

        return redirect()
            ->route('admin.languages.index')
            ->with(["message" => __('alert.update_success'), "msg_type" => "success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        dd("NYI");
    }

    public function languageTeachers($lid)
    {
        if (Auth::user()->hasRole('admin') or Auth::user()->hasRole('teacher') or (Auth::user()->hasRole('student') and Auth::user()->studying()->where('language_id', $lid)->first())) {
            $language = Language::findOrFail($lid);

            $teachers = $language->teachers;
            $students = $language->students;

            return view('admin.languages.lectors_listing')
                ->with('teachers', $teachers)
                ->with('language', $language);
        } else {
            return redirect()->route("admin.languages.index")->with([
                "message" => "Unauthorized",
                "msg_type" => "danger"
            ]);
        }
    }
}
