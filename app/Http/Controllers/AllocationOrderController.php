<?php

namespace App\Http\Controllers;

use App\Models\AllocationOrder;
use App\Models\AllocationOrderItem;
use App\Models\Material;
use App\Models\MaterialStatus;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class AllocationOrderController extends Controller
{
    public function receiveOrderList(Request $request)
    {
        try {
            $user = $request->user();
            $post = Post::where('user_id', $user->id)->get()->last();
            $orders = AllocationOrder::where('to', $post->department_id)->get();
            return response()->json([
                'data' => $orders
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function updateStatus(Request $request, AllocationOrder $order)
    {
        try {
            $status = $request->get('status');
            if ($status === 'ACCEPT') {
                $order->lineItems()->each(function ($lineItem){
                   MaterialStatus::create([
                       'material_id'=>$lineItem->material_id,
                       'status' => 'in-use',
                       'remark'=>'Receive item'
                   ]) ;
                });
            }
            $order->status = $status;
            $order->save();
            return response()->json([
                'data' => $order,
                'message' => Lang::get('messages.allocation_order.change.success')
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    //material allocation order for department
    public function departmentOrders(Request $request)
    {
        try {
            $user = $request->user();
            $post = Post::where('user_id', $user->id)->get()->last();
            $orders = AllocationOrder::where('from', $post->department_id)->get();
            return response()->json([
                'data' => $orders
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function lineItems(Request $request, AllocationOrder $order)
    {
        try {
            return response()->json([
                'data' => AllocationOrderItem::where('allocation_order_id', $order->id)->get()
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function orders(Request $request)
    {
        return response()->json([
            'data' => AllocationOrder::all()
        ], 201);
    }

    public function createOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_no' => 'required',
                'to' => 'required',
                'voucher_date' => 'required',
                'line_items' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }

            $order = AllocationOrder::create([
                'order_no' => $request->get('order_no'),
                'from' => $request->get("from"),
                'to' => $request->get("to"),
                'voucher_date' => $request->get('voucher_date'),
                'created_by' => $request->user() != null ? $request->user()->name : "NA",
                'status' => 'CREATED',
                'remark' => $request->get('remark')
            ]);

            $lineItems = $request->get('line_items');
            $items = [];
            foreach ($lineItems as $key => $value) {
                $material = Material::where('id', $value['id'])->first();

                $item = AllocationOrderItem::create([
                    'allocation_order_id' => $order->id,
                    'material_id' => asset($material) ? $material->id : null,
                    'material' => asset($material) ? $material->name . '(' . $material->code . ')' : null,
                    'quantity' => $value['quantity']
                ]);
                array_push($items, $item);
            }
            $order['line_items'] = $items;

            return response()->json([
                'data' => $order,
                'message' => Lang::get('messages.allocation_order.create.success')
            ], 201);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
