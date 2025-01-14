<?php
namespace App\Helpers;

use App\Models\Role;

class Roles
{
    /**
     *	@description	
     *	@var	
     */
    public static function getByName(string $type)
    {
        return Role::where('type', $type)->first();
    }
    /**
     *	@description	
     *	@var	
     */
    public static function getRoleById(int $id)
    {
        return Role::where('id', $id)->first();
    }
}