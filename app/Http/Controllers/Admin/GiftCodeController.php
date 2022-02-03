<?php

namespace App\Http\Controllers\Admin;

use App\Models\GiftCode;
use App\Models\Language;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GiftCodeController extends Controller
{

    public function __construct()
    {
        $this->middleware("admin");
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|Response|View
     */
    public function index()
    {
        $unused = GiftCode::where('used', 0)->get();
        $used = GiftCode::where('used', 1)->get();

        return view("admin.giftcode.index")
            ->with([
                "used" => $used,
                "unused" => $unused
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|Response|View
     */
    public function create()
    {
        $languages = Language::all();

        return view('admin.giftcode.create')
            ->with("languages", $languages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function store(Request $request)
    {
        $gc = new GiftCode();

        $gc->stars_i = 0;
        $gc->stars_c = 0;
        $gc->package_id = 0 + intval($request->package_id);
        $gc->package_class_count = 0 + intval($request->package_class_count);
        if (!empty($request->language_id)) {
            $gc->language_id = $request->language_id;
        }
        if (!empty($request->comment)) {
            $gc->comment = $request->comment;
        }


        $prefix = $gc->language_id ? strtoupper(substr(Language::findOrFail($gc->language_id)->icon, -2, 2)) : "XX";

        $gc->code = $prefix."-".strtoupper(Str::random(10));

        $gc->save();

        return redirect()->route("admin.gift_codes.index")
            ->with("message", "Poukazka vytvorena")
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
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $languages = Language::all();
        $gc = GiftCode::findOrFail($id);

        return view('admin.giftcode.edit')
            ->with("languages", $languages)
            ->with("gc", $gc);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $gc = GiftCode::findOrFail($id);

        $gc->package_id = 0 + intval($request->package_id);
        $gc->package_class_count = 0 + intval($request->package_class_count);
        if (!empty($request->language_id)) {
            $gc->language_id = $request->language_id;
        }
        if (!empty($request->comment)) {
            $gc->comment = $request->comment;
        }


        $prefix = $gc->language_id ? strtoupper(substr(Language::findOrFail($gc->language_id)->icon, -2, 2)) : "XX";

        $gc->code = $prefix."-".substr($gc->code, 3);

        $gc->save();

        return redirect()->route("admin.gift_codes.index")
            ->with("message", "Poukazka upravena")
            ->with("msg_type", "success");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        GiftCode::destroy($id);
        return redirect()->route("admin.gift_codes.index")
            ->with("message", "Poukazka zmazana")
            ->with("msg_type", "success");
    }
}
