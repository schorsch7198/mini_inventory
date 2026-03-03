<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
	public function store(Request $request)
	{
		$validated = $request->validate([
			'items'            => 'required|array|min:1',
			'items.*.product_id' => 'required|exists:products,id',
			'items.*.quantity'   => 'required|integer|min:1',
		]);

		try {
			$order = DB::transaction(function () use ($validated, $request) {
				$total = 0;
				$orderItems = [];

				foreach ($validated['items'] as $item) {
					// Lock the row for atomic stock update
					$product = Product::lockForUpdate()->find($item['product_id']);

					if ($product->stock < $item['quantity']) {
						throw new \Exception(
							"Insufficient stock for \"{$product->name}\". Available: {$product->stock}, requested: {$item['quantity']}."
						);
					}

					$product->decrement('stock', $item['quantity']);

					$lineTotal = $product->price * $item['quantity'];
					$total += $lineTotal;

					$orderItems[] = [
						'product_id'   => $product->id,
						'product_name' => $product->name,
						'price'        => $product->price,
						'quantity'     => $item['quantity'],
					];
				}

				$order = Order::create([
					'user_id' => $request->user()->id,
					'total'   => $total,
					'status'  => 'completed',
				]);

				$order->items()->createMany($orderItems);

				return $order->load('items');
			});

			return response()->json([
				'message' => 'Order placed successfully.',
				'order'   => $order,
			], 201);

		} catch (\Exception $e) {
			return response()->json([
				'message' => $e->getMessage(),
			], 409);
		}
	}

	public function index(Request $request)
	{
		$orders = $request->user()
			->orders()
			->with('items')
			->latest()
			->paginate(10);

		return response()->json($orders);
	}
}