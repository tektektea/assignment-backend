<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderController extends Controller
{

    public function all(Request $request){
        try {
            return response()->json([
                'data' => PurchaseOrder::all()
            ], 200);
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
    public function lineItems(Request $request, PurchaseOrder $order)
    {
        try {
            return response()->json([
                'data' => $order->lineItems()->get(),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function createOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_no' => 'required',
                'voucher_date'=>'required',
                'line_items'=>'required',
                'amount'=>'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }
            $order=PurchaseOrder::create($request->all());

            $lineItems = $request->get('line_items');
            foreach ($lineItems as $key => $value) {
                $material=Material::where('id',$value['id'])->first();
                $item=PurchaseOrderItem::create([
                    'material_id' => $value['id'],
                    'material' => $material != null ? $material->name . '(' . $material->code . ')' : "NA",
                    'quantity' => $value['quantity'],
                    'purchase_order_id'=>$order->id
                ]);
            }
            return response()->json([
                'data' => $order,
                'message' => Lang::get('messages.purchase_order.create.success')
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
