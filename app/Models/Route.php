<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Route extends Model
{
    protected $fillable = [
        'route', 'uri', 'icon', 'orden'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_routes', 'route_id', 'role_id');
    }
}
