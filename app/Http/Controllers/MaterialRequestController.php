<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Department;
use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MaterialRequestController extends Controller
{
    public function detailRequest(Request $request, MaterialRequest $detail)
    {
        try {

            return response()->json([
                'data' => MaterialRequestItem::where('request_id',$detail->id)->get(),
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
    public function userRequest(Request $request)
    {
        try {
            $user = $request->user();
            $post=Post::where('user_id', $user->id)->get()->last();

            return response()->json([
                'data' =>$post==null?[]: MaterialRequest::where('department_id',$post->department_id)->get(),
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
    public function all(Request $request)
    {
        try {
            return response()->json([
                'data' => MaterialRequest::all()->map(function ($r){
                    $department=Department::where('id',$r->department_id)->select('name')->first();
                    $r['department'] = $department->name;
                    return $r;
                }),
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
    public function create(Request $request)
    {

        try {
            $validator=Validator::make($request->all(),[
                'request_no'=>'required',
                'voucher_date' => 'required',
                'line_items' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }
            $currentUser = $request->user();
            $post=Post::where('user_id',$currentUser->id)->get()->last();
            $lineItem = $request->get('line_items');

            $materialRequest=MaterialRequest::create([
                'request_id'=>$request->get('request_no'),
                'department_id'=>($post!=null)?$post->department_id:null,
                'status'=>'REQUEST',
                'remark'=>$request->get('remark')
            ]);

            $items = [];
            foreach ($lineItem as $key=>$value) {
                $material=Material::where('id',$value['id'])->first();
                $item=MaterialRequestItem::create([
                    'material_id'=>$value['id'],
                    'material'=>($material!=null)?$material->name . "(".$material->code.")":"NA",
                    'request_id'=>$materialRequest->id,
                    'quantity'=>$value['quantity']
                ]);
                array_push($items, $item);
            }
            $materialRequest['line_items'] = $items;

            return response()->json([
                'data' => $materialRequest,
                'message' => Lang::get('messages.material.request.create.success')
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
