<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $user = User::where('id', Auth::user()->id)->firstOrFail();

            if ($user->role === 'client') {
                $order = Order::where('user_id', $user->id)->with("cartItems")->latest()->get();
                return response()->json([
                    "success" => true,
                    "data" => $order
                ]);
            }

            $order = Order::with("cartItems")->latest()->get();
            return response()->json([
                "success" => true,
                "data" => $order
            ]);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json(['message' => 'Erreur au niveau du serveur', 'error' => $e], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        try {
            if (!Auth::user()) {
                return response()->json([
                    "success" => false,
                    "message" => "Vous devez vous connecter avant de procéder au paiement.",
                ], 401);
            }

            if (!Auth::user()->role == 'client') {
                return response()->json([
                    "success" => false,
                    "message" => "Vous devez vous connecter à votre compte client."
                ], 401);
            }

            if (!empty($request->transaction_status) && $request->transaction_status == 'approved') {
                $cart = $request->cart;

                $user = User::where('id', Auth::user()->id)->firstOrFail();

                $order = Order::create([
                    "order_no" => $this->generateCode(),
                    "transaction_id" => request('transaction_id'),
                    "status" => true,
                    'price' => request('total'),
                    "user_id" => $user->id
                ]);

                //dd($order);

                foreach ($cart as $item) {
                    CartItem::create([
                        'order_no' => $order['order_no'],
                        'product_id' => $item['product']['id'],
                        'size' => $item['sizeSelected'] ?? null,
                        'quantity' => $item['quantity'],
                        "user_id" => $user->id,
                    ]);
                }

                return response()->json([
                    "success" => true,
                    "message" => "Commande effectuée avec succès !"
                ], 201);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "Commande non effectuée. Veuillez procéder au paiement."
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur', 'error' => $e], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $order = Order::where('id', $id)->with(["cartItems.product"])->firstOrFail();

            if (!$order) {
                return response()->json([
                    "success" => false,
                    "message" => "Commande non trouvée."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "data" => $order
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur', 'error' => $e], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    function generateCode()
    {
        try {
            do {
                $orderNo = "LMB_" . generateOTP(8);
            } while ($this->otpExists($orderNo));

            return $orderNo;
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    function otpExists($orderNo)
    {
        return Order::where('order_no', $orderNo)->exists();
    }
}
