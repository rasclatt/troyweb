<?php
namespace App\Helpers;

use \App\Interfaces\Helpers\IBase;

/**
 *	@description	Fetches all things site-setting-oriented
 */
abstract class Base implements IBase
{
    /**
     *	@description	Fetches one element from the database
     */
    public static function getOne(string $model, string $type, string $val, string $dto)
    {
        return (new $dto($model::where($type, $val)->first()));
    }
    /**
     *	@description	Get all elements from the database
     */
    public static function getAll(string $model, string $col, string $val, string $dto, $start = null, $max = 100)
    {
        $query = $model::where($col, $val);
        if(!$start)
            return $query->get()->map(fn($user) => new $dto($user))->toArray();
        $paginatedResults = $query->paginate($max, ['*'], 'page', $start);
        return $paginatedResults->map(fn($user) => new $dto($user))->toArray();
    }
}