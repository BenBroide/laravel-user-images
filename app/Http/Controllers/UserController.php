<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserIndexResource;
use App\Http\Resources\UserShowResource;
use App\User;
use Illuminate\Http\Request;
use Auth;

//use Spatie\QueryBuilder\QueryBuilder as QueryBuilder;
//use \Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\QueryBuilder;

//use Spatie\QueryBuilder\AllowedFilter;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        if ($this->isCurrentUserAdmin()) {
            return UserIndexResource::collection(
                QueryBuilder::for(User::class)
                        ->allowedFilters('email')
                        ->allowedIncludes('images')
                ->get()
            );
        };

        return $this->unauthorizedResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // This is handled via passport endpoint api/register.
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if ($this->isCurrentUserAdmin() || $this->isUserCurrentUser($user)) {
            return new UserShowResource(QueryBuilder::for(User::class)
                                                     ->where('id', $user->id)
                                                     ->allowedIncludes('images')
                                                     ->get());
        }

        return $this->unauthorizedResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if ($this->isCurrentUserAdmin() || $this->isUserCurrentUser($user)) {
            $validation_rules = [
                'name'     => 'string|max:255',
                'email'    => 'string|max:255|email|unique:users',
                'password' => 'string|min:8|confirmed'
            ];

            $fields = [ 'name', 'email', 'password' ];

            foreach ($fields as $field) {
                if (isset($request->$field)) {
                    $data         = $this->validate($request, [ $field => $validation_rules[ $field ] ]);
                    $user->$field = $data[ $field ];
                }
            }

            $user->save();

            return response()->json([
                'code'    => 'SUCCESS',
                'message' => 'User updated successfully'
            ]);
        }

         return $this->unauthorizedResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($this->isCurrentUserAdmin()) {
            $user->delete();
            return response()->json([
                'code'    => 'SUCCESS',
                'message' => 'User deleted successfully'
            ]);
        }

        return $this->unauthorizedResponse();
    }

    /**
     * @return mixed
     */
    public function isCurrentUserAdmin()
    {
        $current_user = Auth::user();
        return $current_user->hasRole('admin') ;
    }

    /**
     * @param $user
     *
     * @return bool
     */
    public function isUserCurrentUser($user)
    {
        $current_user = Auth::user();
        return $current_user->id === $user->id;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorizedResponse()
    {
        return response()->json([
            'code'    => 'UNAUTHORIZED',
            'message' => 'Unauthorized'
        ], 401);
    }
}
