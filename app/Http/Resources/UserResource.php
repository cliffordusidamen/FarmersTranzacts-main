<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'email' => $this->email,
            'phone' => $this->phone,
            'email_verified_at' => !$this->email_verified_at
                ? null : Carbon::parse($this->email_verified_at)->toString(),
            'profile_photo' => $this->profile_photo_url,
            'is_active' => (bool) $this->is_active,
            'is_admin' => (bool) $this->is_admin,
            'password_change_required' => (bool) $this->password_change_required,
            'designation' => $this->designation_id
                ? $this->designation->name : null,
            'department_id' => $this->department_id,
            'department' => $this->department_id
                ? $this->department->name : null,
            'department_section_id' => $this->department_section_id,
            'department_section' => $this->department_section_id
                ? $this->departmentSection->name : null,
            'role' => $this->roles_count
                ? $this->roles->first()->only(['id', 'name'])
                : null,
        ];
    }
}
