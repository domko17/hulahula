<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use App\Models\BannerVisibility;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class BannerPostController extends Controller
{

    public function __construct()
    {
        $this->middleware("admin");
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $banners = Banner::all();

        return view('admin.banners.index')
            ->with([
                "banners" => $banners,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $b = new Banner();
        $b->save();

        $bv = new BannerVisibility();
        $bv->banner_id = $b->id;
        $bv->save();

        return redirect()->route('admin.banners.edit', $b->id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        dd('store NIY');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        dd('show NIY');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        $visibility = $banner->visibility;
        $visibility->languages = json_decode($visibility->language);
        $visibility->users = json_decode($visibility->user_id);

        return view('admin.banners.edit')
            ->with([
                "banner" => $banner,
                "visibility" => $visibility,
            ]);
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
        $rules = [
            "title" => "required",
            "description" => "required",
            "type" => "required",
        ];

        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return redirect()->route('admin.banners.index')->with(["message" => __('messages.err_form_not_filled_correctly'), 'msg_type' => "danger"]);

        }

        $banner = Banner::find($id);
        $visibility = $banner->visibility;

        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->type = $request->type;
        $banner->bckg_colour = $request->bg_color;
        if ($request->url) {
            $base_yt = "https://www.youtube.com/embed/";
            $tmp = explode("/", $request->url);
            if (count(explode("=", $tmp[count($tmp) - 1])) > 1) {
                $tmp2 = explode("=", $tmp[count($tmp)-1]);
                $banner->url = $base_yt.$tmp2[count($tmp2) - 1];
            } else {
                $banner->url = $base_yt.$tmp[count($tmp) - 1];
            }
        }
        $banner->ext_link = $request->ext_url;

        //save image and insert into model to save
        if ($banner->type == 2 and $request->hasFile('img') and $request->file('img')->isValid()) {
            $file = $request->file('img');

            $file_path = public_path('/images/banners/');
            if (File::isDirectory($file_path) or File::makeDirectory($file_path, 0777, true, true)) ;

            $file_extension = strtolower($file->getClientOriginalExtension());
            $file_name = "banner_" . time() . "." . $file_extension;

            if (!$file->move($file_path, $file_name)) {
                return redirect()->route('admin.banners.index')
                    ->with("message", __('messages.err_cant_save_uploaded_file'))
                    ->with("msg_type", "danger");
            }

            //delete old image if there was any
            if ($banner->image and file_exists(public_path() . "/images/banners/" . $banner->image)) {
                $file = public_path() . "/images/banners/" . $banner->image;
                unlink($file);
            }

            $banner->image = $file_name;

        } else {
            if ($banner->type == 2 and !$banner->image) {
                return redirect()->route('admin.banners.index')
                    ->with("message", __('messages.err_required_file_not_sent'))
                    ->with("msg_type", "danger");
            }
        }

        $banner->save();

        $visibility->type = $request->visible_all;
        $visibility->language = json_encode($request->visible_lang);
        $visibility->user_id = json_encode($request->visible_users);
        $visibility->students = isset($request->visible_students) ? 1 : 0;
        $visibility->guests = isset($request->visible_guests) ? 1 : 0;
        $visibility->teachers = isset($request->visible_teachers) ? 1 : 0;

        $visibility->save();

        return redirect()
            ->route('admin.banners.index')
            ->with(["message" => "OK", 'msg_type' => "success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $b = Banner::find($id);
        if ($b->image and file_exists(public_path() . "/images/banners/" . $b->image)) {
            $file = public_path() . "/images/banners/" . $b->image;
            unlink($file);
        }

        $bv = $b->visibility;

        BannerVisibility::destroy($bv->id);
        Banner::destroy($id);

        return redirect()->back()->with(["message" => "OK", 'msg_type' => "success"]);
    }

    public function toggleActive($id)
    {
        $b = Banner::find($id);

        $b->active = -intval($b->active) + 1;
        $b->save();
        return redirect()->back()->with(["message" => "OK", 'msg_type' => "success"]);

    }
}
