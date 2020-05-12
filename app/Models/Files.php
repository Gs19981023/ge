<?php

namespace App\Models;

use App\Models\Relations\BelongsToCategoryTrait;
use App\Models\Relations\BelongsToUserTrait;
use App\Models\Relations\MorphManyCommentsTrait;
use App\Models\Relations\MorphManyTagsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Files extends Model
{

    use BelongsToUserTrait, MorphManyTagsTrait, MorphManyCommentsTrait, BelongsToCategoryTrait;
    protected $table = 'files';

    protected $fillable = ['name', 'user_id', 'category_id', 'tags', 'summary', 'status', 'url'];
}
