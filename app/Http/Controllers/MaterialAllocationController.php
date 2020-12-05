<?php

namespace App\Http\Controllers;

use App\Models\AllocationOrder;
use App\Models\Material;
use App\Models\MaterialAllocation;
use App\Models\MaterialAllocationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MaterialAllocationController extends Controller
{


    public function sendMaterial(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'line_items' => 'required',
                'from' => 'required|numeric',
                'to' => 'required|numeric',
                'voucher_date' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }
            $items = [];
            $lineItems = json_decode($request->get('line_items'));
            $allocation=MaterialAllocation::create([
                'allocation_no' => Str::uuid(),
                'from' => $request->get('from'),
                'to' => $request->get('to'),
                'voucher_date' => $request->get('voucher_date'),
            ]);
            foreach ($lineItems as $key => $value) {
                $material=Material::where('id', $value['material_id'])->first();
                $item=MaterialAllocationItem::create([
                    'material_id' => asset($material) ? $material->id : null,
                    'material'=>asset($material)?$material->name."(".$material->code.")":null,
                    'quantity' => $value['quantity'],
                    'allocation_id'=>$allocation->id
                ]);
                array_push($items, $item);
            }
            $allocation['items'] = $items;
            return response()->json([
                'data'=>$allocation,
                'message' => Lang::get('messages.material.sent.success')
            ], 200);

        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
