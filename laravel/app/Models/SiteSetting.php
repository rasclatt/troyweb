<?php
namespace App\Models;

use Illuminate\Database\Eloquent\ {
    Factories\HasFactory,
    Model
};

class SiteSetting extends Model
{
    use HasFactory;

    protected $table = 'site_settings';

    protected $fillable = [
        'type',
        'category',
        'shortcode',
        'content',
        'deleted_at',
    ];
}