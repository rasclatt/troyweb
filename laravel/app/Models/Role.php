<?php
namespace App\Models;

use Illuminate\Database\Eloquent\ {
    Factories\HasFactory,
    Model
};

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'description'];

    /**
     * Get the users for the role.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'rid');
    }
}