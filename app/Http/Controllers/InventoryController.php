<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Material;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function stock(Request $request)
    {
        try {
            $materials = Material::all();

            $inventoryItems = collect($materials)->map(function ($material) {
                $stock = Inventory::where('material_id', $material->id)->get()->sum('quantity');
                return [
                    'id' => $material->id,
                    'material_code' => $material->code,
                    'material_name' => $material->name,
                    'stock' => $stock
                ];
            });
            return response()->json([
                'data' => $inventoryItems
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }


    }

    public function dispatchMaterial(Request $request, Material $material)
    {
        try {
            $validator = Validator::make($request->all(), [
                'quantity' => 'required|numeric'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }
            $inventory = Inventory::create([
                'material_id' => $material->id,
                'material_name' => $material->name . '(' . $material->code . ')',
                'quantity' => -$request->get('quantity'),
                'voucher_date' => $request->has('voucher_date') ? $request->get('voucher_date') : Carbon::now(),
                'created_by' => asset($request->user()) ? $request->user()->name : "unknown",
                'remark' => $request->get('remark')
            ]);
            return response()->json([
                'data' => $inventory,
                'message' => Lang::get('messages.inventory.dispatch.success')
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            Inventory::destroy($id);
            return response()->json([
                'message' => Lang::get('messages.inventory.delete.success')
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
    public function storeMaterial(Request $request, Material $material)
    {
        try {
            $validator = Validator::make($request->all(), [
                'quantity' => 'required|numeric'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }
            $inventory = Inventory::create([
                'material_id' => $material->id,
                'material_name' => $material->name . '(' . $material->code . ')',
                'quantity' => $request->get('quantity'),
                'voucher_date' => $request->has('voucher_date') ? $request->get('voucher_date') : Carbon::now(),
                'created_by' => ($request->user()!=null) ? $request->user()->name : "unknown",
                'remark' => $request->get('remark')!=null?$request->get('remark'):'NA'
            ]);
            return response()->json([
                'data' => $inventory,
                'message' => Lang::get('messages.inventory.store.success')
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
