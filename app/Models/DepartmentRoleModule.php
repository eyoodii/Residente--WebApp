<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentRoleModule extends Model
{
    protected $fillable = ['department_role', 'module', 'access_level'];
}
