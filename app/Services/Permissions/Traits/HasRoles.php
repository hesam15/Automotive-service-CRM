<?php
namespace App\Services\Permissions\Traits;

use App\Models\Role;
use Doctrine\Inflector\Rules\English\Rules;

trait HasRole{
    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    protected function getAllRoles($roles){
        return role::where('name', $roles)->get();
    }

    public function assignRole($role){
        $role = Role::where('id', $role)->first();

        $this->roles()->sync($role->id);

        return $this;
    }

    public function removeRole(...$roles){
        $roles = $this->getAllRoles($roles);

        if($roles->isEmpty()) return $this;

        $this->roles()->detach($roles);

        return $this;
    }

    public function refreshRoles($role){
        $this->roles()->sync($role);

        return $this;
    }

    public function hasRole(...$roles){
        foreach($roles as $role){
            if($this->roles->contains('name', $role)){
                return true;
            }
        }
    }

}