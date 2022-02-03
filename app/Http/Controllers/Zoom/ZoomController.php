<?php


namespace App\Http\Controllers\Zoom;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class ZoomController extends Controller
{

    public function index(Request $request)
    {
        return view('zoom.index')->with("meeting_info", $request->all());
    }

    /**
     * Start Zoom meeting
     *
     */
    public function meeting()
    {
        return view('zoom.meeting');
    }

}
