<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public function routes(){
        return $this->belongsToMany(Route::class, 'role_routes', 'role_id', 'route_id');
    }
}
