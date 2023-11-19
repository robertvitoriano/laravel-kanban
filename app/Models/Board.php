<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Board extends Model
{
    use HasFactory;
    protected $fillable = [
        'title'
    ];
    public function projectLists ():HasMany
    {
        return $this->hasMany(ProjectList::class);
    }

    public function creator():BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function boardMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, BoardMember::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('boardMembers', function(Builder $builder){
           $builder->whereRelation('boardMembers', 'user_id', Auth::id());
        });
    }

}
