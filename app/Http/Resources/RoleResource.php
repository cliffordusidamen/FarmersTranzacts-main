<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $permissions = [];
        
        $this->permissions->map(function($permission, $permKey) use (&$permissions) {
            $permName = $permission->name;
            $modelNameSnakeCase = lastWordInText($permName);
            $modelNameReadable = ucwords(str_replace('_', ' ', $modelNameSnakeCase));
            if (!isset($permissions[$modelNameReadable])) {
                $permissions[$modelNameReadable] = [];
            }

            $permissionName = trim(str_replace($modelNameSnakeCase, '', $permName));

            array_push(
                $permissions[$modelNameReadable],
                spaceCamelCase($permissionName, true)
            );
        });

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => (bool) $this->is_active,
            'users_count' => $this->users_count ?? $this->users()->count(),
            'permissions' => $this->permissions,
            'permissions_grouped' => $permissions,
        ];
    }
}
