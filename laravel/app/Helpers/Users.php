<?php
namespace App\Helpers;

use \App\Dto\Users\Response as UserDto;
use \App\Models\User as Model;
use Illuminate\Support\Facades\Auth;

class Users extends Base
{
    /**
     *	@description	Fetches user by email
     */
    public static function getByEmail(string $email): UserDto
    {
        return self::getOne(Model::class, 'email', $email, UserDto::class);
    }
    /**
     *	@description	Fetches all users
     */
    public static function get(int $start = null, int $max = 100): array
    {
        return array_map(fn($user) => new UserDto($user), Model::all()->toArray());
    }
    /**
     *	@description	Fetches all users
     */
    public static function getById(int $id): UserDto
    {
        return self::getOne(Model::class, 'id', $id, UserDto::class);
    }
    /**
     *	@description	Fetches all users
     */
    public static function delete(int $id, bool $delete = true): int
    {
        return Model::where('id', $id)->update(['deleted_at' => $delete? now() : null]);
    }
    /**
     *	@description	Checks if the user is an admin
     */
    public static function isAdmin(): bool
    {
        return self::isType(__FUNCTION__);
    }
    /**
     *	@description	Checks if the user is a staff librarian
     */
    public static function isLibrarian(): bool
    {
        return self::isType(__FUNCTION__);
    }
    /**
     *	@description	Checks if the user is a community member
     */
    public static function isMember(): bool
    {
        return self::isType(__FUNCTION__);
    }
    /**
     *	@description	Checks if the user is some type
     */
    public static function isType(string $requiredType): bool
    {
        $requiredType = preg_replace('/^is/', '', strtolower($requiredType));
        $type = Auth::user()?->role?->type?? null;
        return $type  === $requiredType;
    }
    /**
     *	@description	Update a user
     */
    public static function update(int $id, $data): UserDto
    {
        if(!empty($data->password)) {
            $data->password = bcrypt($data->password);
        }
        Model::where('id', $id)->update($data);
        return self::getById($id);
    }
    /**
     *	@description	Create a user
     */
    public static function create(array $user)
    {
        return Model::create($user);
    }
}