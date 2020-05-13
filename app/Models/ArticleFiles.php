<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class ArticleFiles extends Model
{

    protected $table = 'article_file';

    protected $fillable = ['article_id', 'filename', 'filepath'];

}
