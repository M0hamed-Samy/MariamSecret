<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('user.account-details', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate(
            [
                'name' => 'required|string|max:255',
                'mobile' => ['required', 'regex:/^01[0125][0-9]{8}$/'],
                'email' => 'required|email|unique:users,email,' . $user->id,
                'old_password' => 'nullable|required_with:new_password|string',
                'new_password' => 'nullable|min:8|confirmed'
            ],
            [
                'mobile.regex' => 'The mobile number must be a valid Egyptian number (e.g., 010xxxxxxxx).',
            ]
        );

        // Update basic info
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->email = $request->email;

        // Handle password change
        if ($request->filled('new_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Old password is incorrect.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Account updated successfully.');
    }
    public function orders()
    {
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(12);
        return view('user.orders', compact('orders'));
    }
    public function account_order_details($order_id)
    {
        $order = Order::where('user_id', Auth::user()->id)->find($order_id);
        if ($order) {
            $orderItems = OrderItem::where('order_id', $order_id)->orderBy('id')->paginate(12);
            $transaction = Transaction::where('order_id', $order_id)->first();
            return view('user.order-details', compact('order', 'orderItems', 'transaction'));
        } else {
            return redirect()->route('login');
        }
    }
    public function account_cancel_order(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->status = "canceled";
        $order->canceled_date = Carbon::now();
        $order->save();
        return back()->with("status", "Order has been cancelled successfully!");
    }
}
