<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
/**
 * Decrypts the ids of the request
 */
class DecryptIds
{
    protected $key;
    protected $iv;

    public function __construct()
    {
        $this->key = config('app.key');
        $this->iv = substr(hash('sha256', $this->key), 0, 16);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->merge(array_map(function ($value) {
            if (is_string($value) && $this->isEncrypted($value)) {
                return self::dec($value);
            }
            return $value;
        }, $request->all()));

        return $next($request);
    }

    public function decrypt($value)
    {
        return openssl_decrypt($value, EncryptIds::$cipher, $this->key, 0, $this->iv);
    }

    protected function isEncrypted($value)
    {
        return !empty(self::dec($value));
    }
    /**
     *	@description	
     *	@var	
     */
    public static function dec(string $param)
    {
        return (new self())->decrypt(@hex2bin($param));
    }
}