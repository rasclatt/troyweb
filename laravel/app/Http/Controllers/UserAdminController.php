<?php
namespace App\Http\Controllers;

use App\Helpers\Users;
use App\Http\Middleware\DecryptIds;
use App\Models\Role;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    /**
     *	@description	Fetches all users
     */
    public function get(Request $request)
    {
        return $this->tryCatch(function() use($request) {
            return $this->toSuccessfulResponse(Users::get($request->start?? null, $request->max?? 100), true, 'data', ['roles' => Role::all(['id', 'type', 'description'])]);
        });
    }
    /**
     *	@description	Delete user
     */
    public function delete(Request $request)
    {
        return $this->tryCatch(function() use($request) {
            $id = DecryptIds::dec($request->route('id'));
            if(empty($id)) {
                return $this->toSuccessfulResponse([], false, 'data', ['error' => 'Invalid User Id'], false);
            }
            $user = Users::getById($id);
            if(empty($user->id)) {
                return $this->toSuccessfulResponse([], false, 'data', ['error' => 'User not found'], false);
            }
            Users::delete($id, empty($user->deleted_at));
            return $this->toSuccessfulResponse(Users::getById($id));
        });
    }
    /**
     *	@description	Update user
     */
    public function update(Request $request)
    {
        return $this->tryCatch(function() use ($request) {
            $id = DecryptIds::dec($request->route('id'));
            if(empty($id)) {
                return $this->toSuccessfulResponse([], false, 'data', ['error' => 'Invalid User Id'], false);
            }
            $body = $request->all();
            unset($body['id']);
            $body['password'] = bcrypt($body['password']);
            return $this->toSuccessfulResponse(Users::update($id, $body));
        });
    }
    /**
     *	@description	Create user
     */
    public function save(Request $request)
    {
        $body = $request->all();
        $body['password'] = bcrypt($body['password']);
        $email = $body['email']?? null;
        if(empty($email)) {
            return $this->toSuccessfulResponse([], false, 'data', ['error' => 'Email is required'], false);
        }
        if(!empty(Users::getByEmail($email)->id)) {
            return $this->toSuccessfulResponse([], false, 'data', ['error' => 'Email already exists'], false);
        }
        return $this->toSuccessfulResponse(Users::create($body));
    }
}