<?php

namespace App\Http\Controllers\Ask;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\User;

class TagController extends Controller
{
    /**
     * tag显示页面
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id, $source_type = 'questions')
    {
        $tag     = Tag::findOrFail($id);
        $sources = [];
        if ($source_type == 'questions') {
            $sources = $tag->questions()->orderBy('created_at', 'desc')->paginate(15);
        } else if ($source_type == 'articles') {
            $sources = $tag->articles()->orderBy('created_at', 'desc')->paginate(15);
        } else if ($source_type == 'files') {
            $sources = $tag->files()->paginate(15);
            for ($i = 0; $i < count($sources); $i++) {
                //  print_r($sources[$i]['user_id']);die;
                $user                     = User::find($sources[$i]['user_id']);
                $sources[$i]['user_name'] = $user['name'];
            }

        }

        $followers = $tag->followers()->orderBy('user_data.credits', 'desc')->orderBy('user_data.supports', 'desc')->take(10)->get();

        return view('theme::tag.index')->with('tag', $tag)
            ->with('sources', $sources)
            ->with('followers', $followers)
            ->with('source_type', $source_type);
    }

}
