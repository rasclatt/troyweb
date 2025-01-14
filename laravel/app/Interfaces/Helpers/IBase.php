<?php
namespace App\Interfaces\Helpers;
/**
 *	@description	Fetches all things site-setting-oriented
 */
interface IBase
{
    /**
     *	@description	Fetches one element from the database
     */
    public static function getOne(string $model, string $val, string $type, string $dto);
}