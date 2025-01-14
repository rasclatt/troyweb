<?php
namespace App\Dto\Users;

use Illuminate\Support\Facades\Auth;

class Response extends \App\Dto\SmartDto
{
    public int $id;
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $password;
    public int $rid;
    public string | array $role;
    public string $status = 'active';
    public ?string $remember_token;
    public ?string $email_verified_at;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    public ?string $deleted_at = null;
}