<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{

    public function all(Request $request)
    {
        return response()->json([
            'data' => Supplier::all(),
        ], 200);
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required',
                'name' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }
            $supplier = Supplier::create($request->all());

            return response()->json([
                'data' => $supplier,
                'message' => Lang::get('messages.supplier.create.success')
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }


    public function update(Request $request, Supplier $supplier)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required',
                'name' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }
//            Supplier::update($request->all());
            $supplier->name = $request->get('name');
            $supplier->code = $request->get('code');
            $supplier->address_one = $request->get('address_one');
            $supplier->address_two = $request->get('address_two');
            $supplier->contact = $request->get('contact');
            $supplier->postal_code = $request->get('postal_code');
            $supplier->save();

            return response()->json([
                'data' => $supplier,
                'message' => Lang::get('messages.supplier.update.success')
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }


    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();

            return response()->json([
                'message' => Lang::get('messages.supplier.delete.success')
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
