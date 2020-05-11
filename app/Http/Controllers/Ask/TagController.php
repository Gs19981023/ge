<?php

namespace App\Http\Controllers\Ask;

use App\Http\Controllers\Controller;
use App\Models\Tag;

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
            // $sources = array(
            //     array('id' => 1, 'name' => '文件名1', 'desc' => '  Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.', 'url' => '11111'),
            //     array('id' => 2, 'name' => '文件名2', 'desc' => '  Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.', 'url' => '11111'),
            //     array('id' => 3, 'name' => '文件名3', 'desc' => '  Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.', 'url' => '11111'),
            //     array('id' => 4, 'name' => '文件名4', 'desc' => '  Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.', 'url' => '11111'),
            // );
            $sources = $tag->files()->paginate(15);

        }

        $followers = $tag->followers()->orderBy('user_data.credits', 'desc')->orderBy('user_data.supports', 'desc')->take(10)->get();

        return view('theme::tag.index')->with('tag', $tag)
            ->with('sources', $sources)
            ->with('followers', $followers)
            ->with('source_type', $source_type);
    }

}
