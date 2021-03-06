<?php

namespace App;

use App\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasPermissions;

    public function permissions () {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

    public function hasPermissionTo (...$permissions) {
        //$role->hasPermissionTo('edit-user', 'edit-issue');
        return $this->permissions()->whereIn('slug', $permissions)->count();
    }
}
