<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(
    'project_code',
    'project_name',
    'client',
    'budget',
    'status',
)]
class Project extends Model {}
