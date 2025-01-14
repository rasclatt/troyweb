<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
/**
 * Encrypts the ids of the response
 */
class EncryptIds
{
    # Whitelist of keys to encrypt
    public static $filterKeys = [
    ];
    public static $cipher = 'AES-256-CBC';
    protected $key;
    protected $iv;

    public function __construct()
    {
        $this->key = config('app.key'); // Use the application key
        $this->iv = substr(hash('sha256', $this->key), 0, 16); // Generate an IV from the key
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
        $response = $next($request);
        if ($response->isSuccessful() && $response->headers->get('content-type') === 'application/json') {
            $data = $response->getData(true);
            array_walk_recursive($data, function (&$item, $key) {
                if (preg_match('/.*id$/i', $key) && !in_array($key, self::$filterKeys)) {
                    $item = bin2hex($this->encrypt($item));
                }
            });

            $response->setData($data);
        }

        return $response;
    }

    public function encrypt($value)
    {
        return openssl_encrypt($value, self::$cipher, $this->key, 0, $this->iv);
    }
    /**
     *	@description	
     *	@var	
     */
    public static function enc($value)
    {
        return bin2hex((new self($value))->encrypt($value));
    }
}
