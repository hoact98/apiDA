<?php
namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;

trait HasPermissionsTrait{
    public function roles() {
        return $this->belongsToMany(Role::class,'user_roles');
  
     }
  
     public function hasRole(...$roles) {
          foreach ($roles as $role) {
              if ($this->roles->contains('slug', $role)) {
                  return true;
              }
          }
          return false;
      }
       
      public function hasPermission($permission) {
          return (bool) $this->permissions->where('slug', $permission->slug)->count();
      }
  
      protected function getAllPermissions($permissions)
      {
          return Permission::whereIn('slug', $permissions)->get();
      }
  
      public function givePermissionsTo(...$permissions) {
          $permissions = $this->getAllPermissions($permissions);
          if($permissions === null) {
             return $this;
          }
          $this->permissions()->saveMany($permissions);
          return $this;
      }
  
      public function deletePermissions(...$permissions) {
          $permissions = $this->getAllPermissions($permissions);
          $this->permissions()->detach($permissions);
          return $this;
      }
}
?>