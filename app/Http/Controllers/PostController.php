<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function userPosts(Request $request)
    {
        try {
            $users = User::role('district-manager')->get();
            $usersPosts=collect($users)->map(function ($user){
                $post=Post::where('user_id', $user->id)->get()->last();
                if ($post!=null){
                    $user['department'] = Department::where('id', $post->department_id)->first()->name;
                    $user['department_id'] = Department::where('id', $post->department_id)->first()->id;
                }
                return $user;
            });
            return response()->json([
                'data' => $usersPosts,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function assignPost(Request $request, User $user)
    {
        try {
            $validator = Validator::make($request->all(), [
                'joining_date' => 'required',
                'user_id' => 'required',
                'department_id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => implode(PHP_EOL, $validator->getMessageBag()->all())
                ], 400);
            }
            $lastPost = Post::where('user_id', $request->get('user_id'))->get()->last();
            if ($lastPost!=null) {
                $lastPost->leaving_date = $request->has('leaving_date') ? $request->get('leaving_date') : null;
                $lastPost->save();
            }
            $newPost = Post::create($request->all());

            return response()->json([
                'data' => $newPost,
                'message' => Lang::get('messages.post.assign.success')
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

}
