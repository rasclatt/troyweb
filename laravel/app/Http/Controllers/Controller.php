<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     *	@description	
     *	@var	
     */
    protected function toSuccessfulResponse(
        $data = null,
        bool $count = false,
        $key = 'data',
        array $extra = null,
        bool $success = true
    )
    {
        $d = [
            'success' => $success,
            $key => $data,
        ];
        if($count) {
            $d['count'] = count($data);
        }
        if($extra !== null) {
            $d = array_merge($d, $extra);
        }
        return response()->json($d);
    }
    /**
     *	@description	
     *	@var	
     */
    protected function tryCatch(callable $func)
    {
        try {
            return $func();
        } catch (\Exception $e) {
            return $this->toSuccessfulResponse([], false, 'data', ['error' => $e->getMessage()], false);
        }
    }
    /**
     *	@description	
     *	@var	
     */
    protected function baseSave(
        Request | array $request,
        string $dto,
        string $model,
        string $fileName = null,
        string $columnFileName = null,
        int $uid = null
    )
    {
        return $this->tryCatch(function() use($request, $dto, $model, $fileName, $columnFileName, $uid) {
            $request->validate($dto::getValidations());
            $data = (!is_array($request))? $request->all() : $request;
            $id = ($data['id'])?? null;
            if(!empty($uid))
                $data['uid'] = $uid;
            $dto = (new $dto($data))->toArray();
            if ($request->hasFile($fileName)) {
                $image = $request->file($fileName);
                $imagePath = $image->store('images', 'frontend');
                $dto[$columnFileName] = "/{$imagePath}";
            }
            if ($id) {
                $modelQuery = $model::findOrFail($id);
                $modelQuery->update($dto);
            } else {
                $modelQuery = $model::create($dto);
            }
            return $this->toSuccessfulResponse($modelQuery);
        });
    }
}
