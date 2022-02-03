<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\StarOrder;
use App\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BuyStarsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|Response|View
     */
    public function index()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $profile = $user->profile;
        $orders_old = $user->starOrders;
        $orders = $user->packageOrders;

        return view('buy_stars.index')
            ->with('profile', $profile)
            ->with('orders', $orders)
            ->with('orders_old', $orders_old);
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
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $order = StarOrder::findOrFail($id);

        $order->canceled = 1;
        $order->save();

        return redirect()->route('buy_stars.index')->with('message', __('success'))->with("msg_type", 'success');

    }
}
