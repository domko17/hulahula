<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmailMessageGenerator;
use App\Models\Helper;
use App\Models\PackageOrder;
use App\Models\UserPackage;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PackageOrderController extends Controller
{

    public function __construct()
    {
        $this->middleware(["admin"])->except("destroy");
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $orders_paid = PackageOrder::where('paid', 1)->orWhere('canceled', 1)->get();
        $orders_unpaid = PackageOrder::where('paid', 0)->where('canceled', 0)->get();

        return view('admin.package_order.index')
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
     * @return Response
     */
    public function store(Request $request)
    {
        //
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
     * @return Response
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
        $order = PackageOrder::findOrFail($id);

        $order->canceled = 1;
        $order->save();

        if (!Auth::user()->hasRole('admin'))
            return redirect()->route('buy_stars.index')->with('message', __('success'))->with("msg_type", 'success');

        return redirect()->route('admin.package-orders.index')->with('message', __('success'))->with("msg_type", 'success');
    }

    public function signAsPaid($id)
    {
        $order = PackageOrder::findOrFail($id);

        /**
         * @var User $user
         */
        $user = $order->user;

        $was_renew = false;

        {// log users new package
            $user_package = $user->currentPackage;
            if (!$user_package) {
                $new_up = new UserPackage();
                $new_up->user_id = $user->id;
                $new_up->type = $order->package_id;
                $new_up->state = 1;
                $new_up->classes_left = Helper::PACKAGES[$order->package_id]['classes_count'];
                $new_up->save();
            } else {
                //Ak ma balicek a nema nasledujuci balicek, vytvorit nasledujuci balicek
                if ($user_package->type == 99) { //if has STARTER, cancel it and set new package
                    $user_package->state = 3;
                    $user_package->save();

                    $new_up = new UserPackage();
                    $new_up->user_id = $user->id;
                    $new_up->type = $order->package_id;
                    $new_up->state = 1;
                    $new_up->classes_left = Helper::PACKAGES[$order->package_id]['classes_count'];
                    $new_up->save();
                } else { // else set renewal package
                    $renew_up = new UserPackage();
                    $renew_up->user_id = $user->id;
                    $renew_up->type = $order->package_id;
                    $renew_up->state = 4;
                    $renew_up->classes_left = Helper::PACKAGES[$order->package_id]['classes_count'];
                    $renew_up->save();

                    $user_package->renewal_package_id = $renew_up->id;
                    $user_package->save();

                    $was_renew = true;
                }
            }
        }

        $order->paid = 1;
        $order->save();

        EmailMessageGenerator::generateEmail(
            'order_paid',
            array(
                'recipients' => $user->email,
                'package_type' => $order->package_id,
                'was_renewal' => $was_renew,
                'order_id' => $order->id,
            )
        );

        return redirect()->route('admin.package-orders.index')->with('message', __('success'))->with("msg_type", 'success');

    }
}
