<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Files;
use App\Models\Tag;
use App\Services\CaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    public function create()
    {
        return view("theme::file.create", ['message' => '']);
    }
    public function download($filename)
    {
        return response()->download(realpath(base_path('/')) . '\\storage\\app\\' . str_replace("-", "\\", $filename), $filename);
    }

    public function store(Request $request, CaptchaService $captchaService)
    {
        $loginUser = $request->user();

        $request->flash();

        // $this->validate($request, $this->validateRules);

        $data = [
            'user_id'     => $loginUser->id,
            'category_id' => intval($request->input('category_id', 0)),
            'name'        => trim($request->input('title')),
            'summary'     => $request->input('summary'),
            'status'      => 1,
        ];

        if ($request->hasFile('file')) {

            $file      = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filePath  = 'files/' . gmdate("Y") . "/" . gmdate("m") . "/" . uniqid(str_random(8)) . '.' . $extension;
            Storage::disk('local')->put($filePath, File::get($file));
            $data['url'] = str_replace("/", "-", $filePath);
        }

        $file = Files::create($data);

        /*判断问题是否添加成功*/
        if ($file) {

            /*添加标签*/
            $tagString = trim($request->input('tags'));
            Tag::multiSave($tagString, $file);

            //记录动态
            $this->doing($file->user_id, 'create_file', get_class($file), $file->id, $file->name, $file->summery);

            // /*用户提问数+1*/
            // $loginUser->userData()->increment('articles');

            // UserTag::multiIncrement($loginUser->id, $article->tags()->get(), 'articles');

            // $this->credit($request->user()->id, 'create_article', Setting()->get('coins_write_article'), Setting()->get('credits_write_article'), $article->id, $article->title);

            // if ($file->status === 1) {
            //     $message = '文章发布成功!' . get_credit_message(Setting()->get('credits_write_article'), Setting()->get('coins_write_article'));
            // } else {
            //     $message = '文章发布成功！为了确保文章的质量，我们会对您发布的文章进行审核。请耐心等待......';
            // }

            // $this->counter('article_num_' . $article->user_id, 1, 60);
            $message = "";
            return view("theme::file.create", ['message' => '上传成功']);

        }

        return view("theme::file.create", ['message' => '上传失败']);

    }

    public function uploadfile(Request $request, CaptchaService $captchaService)
    {

        $res = array('code' => 0,
            'msg'               => "上传失败");

        if ($request->hasFile('file')) {

            $file      = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filePath  = 'files/' . gmdate("Y") . "/" . gmdate("m") . "/" . uniqid(str_random(8)) . '.' . $extension;
            Storage::disk('local')->put($filePath, File::get($file));

            $res = array('code' => 1,
                'msg'               => "上传成功",
                'url'               => str_replace("/", "-", $filePath));
        }

        return json_encode($res);
    }
}
