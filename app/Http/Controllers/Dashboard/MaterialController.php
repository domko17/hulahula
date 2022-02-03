<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\ClassMaterial;
use App\Models\Language;
use App\Models\Material;
use App\Models\User\Student;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MaterialController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (Auth::user()->hasRole("teacher") or Auth::user()->hasRole("admin")) {
                return $next($request);
            }

            return redirect()
                ->route('dashboard')
                ->with("message", "Access denied")
                ->with("msg_type", "danger");
        })->except(["download", "studentsMaterial"]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|Response|View
     */
    public function index()
    {
        $materials = Material::all();
        $res = array();

        if (Auth::user()->hasRole('admin') or Auth::user()->hasRole('teacher')) {
            $res = $materials;
        } else {
            $my_langs = Auth::user()->studying()->pluck('languages.id');
            foreach ($materials as $m) {
                if (in_array($m->language->id, $my_langs)) ;
                $res[] = $m;
            }
        }

        return view('materials.index')
            ->with([
                "material" => $res
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|Response|View
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->hasRole("admin")) {
            $languages = Language::all();
        } else {
            $languages = $user->teaching;
        }

        return view('materials.create')
            ->with([
                "languages" => $languages
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function store(Request $request)
    {
        $rules = [
            "name" => "required",
            "language_id" => "required",
            "type" => "required"
        ];

        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->with("message", __('messages.err_form_not_filled_correctly'))
                ->with("msg_type", "danger");
        }

        $mat = new Material();

        $mat->language_id = $request->language_id;
        $mat->added_by = Auth::id();
        $mat->type = $request->type;
        $mat->name = $request->name;

        if ($mat->type == 1 or $mat->type == 2) {
            $mat->content = $request->link;
        } elseif ($mat->type == 3) {
            if ($request->hasFile('file_1') and $request->file('file_1')->isValid()) {
                $file = $request->file('file_1');

                $file_path = public_path('/files/materials/');
                if (File::isDirectory($file_path) or File::makeDirectory($file_path, 0777, true, true)) ;

                $file_extension = strtolower($file->getClientOriginalExtension());
                $file_name = "material_" . time() . "." . $file_extension;

                if (!$file->move($file_path, $file_name)) {
                    return redirect()->back()
                        ->with("message", __('messages.err_cant_save_uploaded_file'))
                        ->with("msg_type", "danger");
                }

                $mat->content = $file_name;

            } else {
                return redirect()->back()
                    ->with("message", __('messages.err_required_file_not_sent'))
                    ->with("msg_type", "danger");
            }
        } else {
            //default
        }

        $mat->save();

        return redirect()->route("materials.index")
            ->with("message", __('messages.ok_material_added'))
            ->with("msg_type", "success");

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function destroy($id)
    {
        $mat = Material::findOrFail($id);
        if (Auth::user()->hasRole('admin') or $mat->added_by == Auth::id()) {
            $file = public_path() . "/files/materials/" . $mat->content;
            if ($mat->type == 3) {
                if (file_exists($file) and unlink($file)) {
                    Material::destroy($id);
                } else return redirect()->back()->with("message", "Nezmazané: chyba pri mazaní súboru.")->with('msg_type', "danger");
            }
            else{
                Material::destroy($id);
            }
        }
        return redirect()->back()->with("message", "Zmazané")->with('msg_type', "success");
    }

    /**
     * @param $id
     * @return RedirectResponse|BinaryFileResponse
     */
    public function download($id)
    {
        //
        $mat = Material::findOrFail($id);

        if (in_array($mat->type, [1, 2])) {
            return redirect()
                ->back()
                ->with("message", "**cannot download this material")
                ->with("msg_type", "danger");
        } else {
            $file = public_path() . "/files/materials/" . $mat->content;
            return response()->download($file, $mat->content);
        }
    }

    /**
     * @param $sid
     * @return Factory|RedirectResponse|View
     */
    public function studentsMaterial($sid)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin') and (!$user->hasRole('student') or $user->id != $sid)) {
            return redirect()->back()->with(['message' => __('messages.access_denied'), 'msg_type' => "danger"]);
        }

        $student = Student::find($sid);

        if (!$student) {
            return redirect()->back()->with(['message' => "Chyba: Užívateľ nie je študent", 'msg_type' => "danger"]);

        }

        $past_classes = $student->classes_all()->pluck('classes.id');
        $tmp = ClassMaterial::whereIn('class_id', $past_classes->toArray())->pluck('material_id');
        $mat = Material::whereIn('id', $tmp)->get();

        return view('materials.index')
            ->with([
                "material" => $mat
            ]);
    }
}
