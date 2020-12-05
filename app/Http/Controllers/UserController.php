<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function all(Request $request)
    {
        try {
            $users=User::all();

            return response()->json([
                'data' => $users,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'messages' => $exception->getMessage()
            ], 400);
        }
    }


    public function store(Request $request)
    {
        try {
            $validator=Validator::make($request->all(),[
                'name'=>'required',
                'email'=>'required|email|unique:users',
                'password'=>'required',
                'role' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }
            $request->merge([
                'password' => Hash::make($request->password)
            ]);
            DB::beginTransaction();

            $user = User::create($request->all());
            $user->assignRole($request->get('role'));

            DB::commit();
            return response()->json([
                'data' => $user,
                'message' => 'User created successfully'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'message' => Lang::get("messages.user.not_exist")
                ], 400);
            }

            $user = $user->update($request->all());

            return response()->json([
                'data' => $user,
                'message' => Lang::get("messages.user.create.success")
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function managerSelect(Request $request)
    {
        try {
            $users=User::role('district-manager')->get();
            $managers=collect($users)->map(function ($user){
                 return [
                     'id'=>$user->id,
                     'value'=>$user->id,
                     'label'=>$user->name.'('.$user->email.')'
                 ];
            });
            return response()->json([
                'data' => $managers,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
    public function delete(User $user)
    {
        try {
            $user->delete();

            return response()->json([
                'message' => 'User deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
