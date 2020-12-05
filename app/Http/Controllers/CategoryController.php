<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function all(Request $request)
    {
        return response()->json([
            'data' => Category::all()
        ], 200);
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }
            $category = Category::create($request->all());

            return response()->json([
                'data' => $category,
                'message' => Lang::get('messages.category.create.success')
            ], 201);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }

    }


    public function update(Request $request, Category $category)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }
            $category->name = $request->get('name');
            $category->description = $request->get('description');
            $category->save();
            return response()->json([
                'data' => $category,
                'message' => Lang::get('messages.category.update.success')
            ], 201);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function categorySelect(Request $request)
    {
        try {
            $values=collect(Category::all())->map(function ($cat){
                $obj = [];
                $obj['id']=$cat->id;
                $obj['value']=$cat->id;
                $obj['label'] = $cat->name;
                return $obj;
            });
            return response()->json([
                'data' => $values
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return response()->json([
                'message' => Lang::get('messages.category.delete.success')
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
