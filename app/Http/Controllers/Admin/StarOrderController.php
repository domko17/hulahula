<?php

namespace App\Http\Controllers\Admin;

use App\Models\StarOrder;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Ramsey\Uuid\Codec\OrderedTimeCodec;

class StarOrderController extends Controller
{

    public function __construct()
    {
        $this->middleware("admin");
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $orders_paid = StarOrder::where('paid', 1)->orWhere('canceled', 1)->get();
        $orders_unpaid = StarOrder::where('paid', 0)->where('canceled', 0)->get();

        return view('admin.star_order.index')
            ->with('orders_finished', $orders_paid)
            ->with('orders_unpaid', $orders_unpaid);
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
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
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
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $order = StarOrder::findOrFail($id);

        $order->canceled = 1;
        $order->save();

        return redirect()->route('admin.star-orders.index')->with('message', __('success'))->with("msg_type", 'success');
    }

    public function signAsPaid($id)
    {
        $order = StarOrder::findOrFail($id);

        $user = $order->user->profile;

        $user->stars_individual += $order->stars_i;
        $user->stars_collective += $order->stars_c;

        $user->save();

        $order->paid = 1;
        $order->save();

        //TODO: odoslat mail studaklovi ze bola evidovana platba a ze ma hviezdicky na konte

        return redirect()->route('admin.star-orders.index')->with('message', __('success'))->with("msg_type", 'success');

    }
}
