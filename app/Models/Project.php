<?php

namespace App\Models;

use App\Models\Scopes\UserProjectsScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[ScopedBy([UserProjectsScope::class])]
class Project extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'user_id'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
