<?php

namespace App\Http\Controllers;

use App\Models\AllocationOrder;
use App\Models\AllocationOrderItem;
use App\Models\Department;
use App\Models\Material;
use App\Models\MaterialStatus;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{

    public function departmentMaterials(Request $request)
    {
        try {
            $user = $request->user();
            $post = Post::where('user_id', $user->id)->get()->last();
            $department = Department::where('id', $post->department_id)->first();
            $materials=DB::table('materials')
                ->join('allocation_order_items', 'materials.id', '=', 'allocation_order_items.material_id')
                ->join('allocation_orders','allocation_orders.id','=','allocation_order_items.allocation_order_id')
                ->where('allocation_orders.to','=',$department->id)
                ->where('allocation_orders.status','=','ACCEPT')
                ->distinct('materials.id')
                ->get();

            $data = [];
            foreach ($materials as $key => $value) {
                $status=MaterialStatus::where('material_id',$value->material_id)->get()->last();
                $value->material_status = $status != null ? $status->status : "";
                array_push($data, $value);
            }
            return response()->json([
                'data' => $data
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function all(Request $request)
    {
        return response()->json([
            'data' => Department::all()
        ], 200);
    }

    public function select(Request $request)
    {
        return response()->json([
            'data' => collect(Department::all())->map(function ($department) {
                return [
                    'id' => $department->id,
                    'value' => $department->id,
                    'label' => $department->name
                ];
            })
        ], 200);
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required',
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }
            $department = Department::create($request->all());
            return response()->json([
                'data' => $department,
                'message' => Lang::get('messages.department.create.success')
            ], 201);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }


    public function update(Request $request, Department $department)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required',
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }
            $department->update($request->all());

            return response()->json([
                'data' => $department,
                'message' => Lang::get('messages.department.update.success')
            ], 201);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function destroy(Department $department)
    {
        try {

            $department->delete();
            return response()->json([
                'message' => Lang::get('messages.department.delete.success')
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
