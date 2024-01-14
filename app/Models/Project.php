<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'project_list_id',
        'creator_id',
        'order',
        'description'
    ];
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, Member::class);
    }

    public function projectList(): BelongsTo
    {
        return $this->belongsTo(ProjectList::class, 'project_list_id');
    }

    protected static function booted(): void
    {
        if ( Auth::user() && Auth::user()->level == 'user') {

            static::addGlobalScope('member', function (Builder $builder) {
                $builder->whereRelation('members', 'user_id', Auth::id());
            });
        }
    }
}
