<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use App\Models\WordCard;
use App\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;

class WordCardsController extends Controller
{
    public function __construct()
    {
        $this->middleware("admin")->only(["index"]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = Language::all();

        return view('admin.word_cards.index')
            ->with("languages", $languages);
    }

    public function indexLanguage($id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRole("admin") or ($user->hasRole("teacher") and $user->teaching->where("language_id", $id)->first)) {
            $l = Language::findOrFail($id);
            $cards = $l->word_cards;

            return view('admin.word_cards.index_lang')
                ->with([
                    "language" => $l,
                    "cards" => $cards,
                ]);
        }

        return redirect()->route("dashboard")
            ->with("message", "Unauthorized action / Nepovolená akcia")
            ->with("msg_type", "danger");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function create($id = 0)
    {
        if ($id == 0) {
            return redirect()->back()->with("message", "Nesprávny dopyt. Chýbajúce dáta")->with("msg_type", "danger");
        }

        $user = User::find(Auth::id());
        if ($user->hasRole("admin") or ($user->hasRole("teacher") and $user->teaching->where("language_id", $id)->first)) {
            $language = Language::findOrFail($id);

            return view('admin.word_cards.create')
                ->with('language', $language);
        }

        return redirect()->route("dashboard")
            ->with("message", "Unauthorized action / Nepovolená akcia")
            ->with("msg_type", "danger");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'language_id' => "required",
            'image_base64' => "required",
            'word_sk' => "required",
            'word_native' => "required",
            'word_diff' => "required",
        ];

        try {
            $this->validate($request, $rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with("message", "Nesprávny dopyt. Chýbajúce dáta")->with("msg_type", "danger");
        }

        $user = User::find(Auth::id());
        if ($user->hasRole("admin") or ($user->hasRole("teacher") and $user->teaching->where("language_id", $request->language_id)->first)) {

            $data = $request->image_base64;
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            $imageName = time() . '.png';
            $filePath = public_path('/images/word_cards/' . $request->language_id);

            if (File::isDirectory($filePath) or File::makeDirectory($filePath, 0777, true, true)) ;
            file_put_contents($filePath . '/' . $imageName, $data);

            $card = new WordCard();

            $card->language_id = $request->language_id;
            $card->language_level = $request->word_diff;
            $card->word_slovak = $request->word_sk;
            $card->word_native = $request->word_native;
            $card->image = $imageName;

            $card->save();

            return redirect()->route("admin.word_cards.index_language", $request->language_id)
                ->with("message", "Kartička pridaná")
                ->with("msg_type", "success");
        }

        return redirect()->route("dashboard")
            ->with("message", "Unauthorized action / Nepovolená akcia")
            ->with("msg_type", "danger");
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
        $word = WordCard::findOrFail($id);

        $user = User::find(Auth::id());
        if ($user->hasRole("admin") or ($user->hasRole("teacher") and $user->teaching->where("language_id", $word->language->id)->first)) {
            return view("admin.word_cards.edit")
                ->with("word", $word);
        }

        return redirect()->route("dashboard")
            ->with("message", "Unauthorized action / Nepovolená akcia")
            ->with("msg_type", "danger");
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
        $rules = [
            'language_id' => "required",
            'image_base64' => "required",
            'word_sk' => "required",
            'word_native' => "required",
            'word_diff' => "required",
        ];

        try {
            $this->validate($request, $rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with("message", "Nesprávny dopyt. Chýbajúce dáta")->with("msg_type", "danger");
        }


        $card = WordCard::findOrFail($id);
        $user = User::find(Auth::id());
        if ($user->hasRole("admin") or ($user->hasRole("teacher") and $user->teaching->where("language_id", $card->language->id)->first)) {

            //$card->language_id = $request->language_id;
            $card->language_level = $request->word_diff;
            $card->word_slovak = $request->word_sk;
            $card->word_native = $request->word_native;

            {
                $data = $request->image_base64;
                list($type, $data) = explode(';', $data);
                list(, $data) = explode(',', $data);
                $data = base64_decode($data);
                $imageName = time() . '.png';
                $filePath = public_path('/images/word_cards/' . $request->language_id);

                if (File::isDirectory($filePath) or File::makeDirectory($filePath, 0777, true, true)) ;
                file_put_contents($filePath . '/' . $imageName, $data);

                if ($card->image) {
                    if (file_exists($filePath . "/" . $card->image)) {
                        unlink($filePath . "/" . $card->image);
                    }
                }
            }

            $card->image = $imageName;

            $card->save();

            return redirect()->route("admin.word_cards.index_language", $request->language_id)
                ->with("message", "Kartička upravená")
                ->with("msg_type", "success");
        }

        return redirect()->route("dashboard")
            ->with("message", "Unauthorized action / Nepovolená akcia")
            ->with("msg_type", "danger");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function teacherIndex(){
        $languages = Auth::user()->teaching;

        return view('admin.word_cards.index')
            ->with("languages", $languages);
    }
}
