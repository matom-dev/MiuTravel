<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';
    protected $fillable = ['name', 'display_name', 'description', 'group_permission_id', 'created_at', 'updated_at'];
    public $timestamps = true;

    public function groups()
    {
        return $this->belongsTo(GroupPermission::class, 'group_permission_id', 'id');
    }
}
