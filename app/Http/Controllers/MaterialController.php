<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    public function locate(Request $request, Material $material)
    {
        try {

            $status=MaterialStatus::where('material_id',$material->id)->get()->last();
            $value=DB::table('allocation_orders')
                ->leftJoin('allocation_order_items','allocation_order_items.allocation_order_id','=','allocation_orders.id')
                ->leftJoin('departments','departments.id','=','allocation_orders.to')
                ->where('allocation_order_items.material_id','=',$material->id)
                ->where('allocation_orders.status','=','ACCEPT')
                ->select(['departments.name','allocation_orders.voucher_date'])
                ->get();
            return response()->json([
                'data' => $value,
                'status'=>$status
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'messages' => $exception->getMessage()
            ], 400);
        }
    }
    public function all()
    {
        $materials = Material::all();

        return response()->json([
            'data' => $materials
        ], 200);
    }
    public function changeStatus(Request $request,Material $material)
    {
        try {
            $validator=Validator::make($request->all(),[
                'status'=>'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }
            $material['status'] = $request->get('status');
            MaterialStatus::create([
                'material_id'=>$material->id,
                'remark' => $request->get('remark'),
                'status' => $request->get('status'),
            ]);
            return response()->json([
                'data' => $request->get('status'),
                'message' => Lang::get('messages.material.status.update.success')
            ], 201);
        }catch (\Exception $exception){
            return response()->json([
                'messages' => $exception->getMessage()
            ], 400);
        }
    }
    public function materialSelect(Request $request){
        try {
             $materials=collect(Material::all())->map(function ($material){
                return[
                    'id'=>$material->id,
                    'value'=>$material->id,
                    'label'=>$material->name,
                    'cost_price' => $material->cost_price
                ];
            });
            return response()->json([
                'data' => $materials
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }

    }

    public function categories(Material $material)
    {
        return response()->json([
            'data' => $material->categories()->get()
        ], 200);
    }

    public function store(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:materials',
                'name' => 'required',
                'cost_price' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }
            $material = Material::create($request->all());
            MaterialStatus::create([
                'material_id'=>$material->id,
                'remark'=>'Created',
                'status'=>'idle'
            ]);
            $material->categories()->attach($request->get('categories'));
            return response()->json([
                'data'=>$material,
                'message' => Lang::get('messages.material.create.success')
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }

    }

    public function update(Request $request, $id)
    {
        try {
            $material = Material::where('id', $id)->first();
            if ($material === null) {
                return response()->json([
                    'message' => Lang::get('messages.material.not_exist')
                ], 400);
            }
            $material->code = $request->get('code');
            $material->name = $request->get('name');
            $material->cost_price = $request->get('cost_price');
            $material->serial_no = $request->get('serial_no');
            $material->color = $request->get('color');
            $material->manufacture = $request->get('manufacture');
            $material->save();

            $material->categories()->detach();
            $material->categories()->attach($request->get('categories'));

            return response()->json([
                'data' => $material,
                'message' => Lang::get('messages.material.update.success')
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }

    }

    public function destroy(Request $request, Material $material)
    {
        try {
            $material->delete();
            return response()->json([
                'message' => Lang::get('messages.material.delete.success')
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
