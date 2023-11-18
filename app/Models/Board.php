<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Board extends Model
{
    use HasFactory;
    protected $fillable = [
        'title'
    ];
    public function ProjectLists ():HasMany
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

}
