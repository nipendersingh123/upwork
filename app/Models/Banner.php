<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'type', 'category_id', 'project_id', 'status'];

    public function category()
    {
        return $this->belongsTo(\Modules\Service\Entities\Category::class, 'category_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

}
