@extends('theme::layout.public')

@section('seo_title')分享资料 - {{ Setting()->get('website_name') }}@endsection

@section('css')
    <link href="{{ asset('/static/js/summernote/summernote.css')}}" rel="stylesheet">
    <link href="{{ asset('/static/js/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/static/js/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet">
@endsection

@section('content')

    <div class="row mt-10">
    {{--     <ol class="breadcrumb">
            <li><a href="{{ route('website.blog') }}">资料</a></li>
            <li class="active">分享资料</li>
        </ol> --}}

        <div style="color: red;text-align: center;font-size: 30px">    {{ $message }}</div>
        <form id="article_form" method="POST" role="form" enctype="multipart/form-data" action="{{ route('blog.file.store') }}">
            <input type="hidden" id="editor_token" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="tags" name="tags" value="" />

            <div class="form-group @if($errors->has('title')) has-error @endif ">
                <label for="title">文件标题:</label>
                <input id="title" type="text" name="title"  class="form-control input-lg" placeholder="Java面试题（一）" value="{{ old('title','') }}" />
                @if($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
            </div>
            <div class="form-group @if($errors->has('file')) has-error @endif">
                <label>资料上传:</label>
                <input type="file" name="file"/>
                @if($errors->has('logo')) <p class="help-block">{{ $errors->first('file') }}</p> @else <p class="help-block">建议大小不要过大</p> @endif
            </div>
           

            <div class="form-group">
                <label for="editor">资料描述：</label>
                <textarea name="summary" class="form-control" placeholder="资料描述">{{ old('summary','') }}</textarea>
            </div>


            <div class="row">
                <div class="col-xs-4">
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="0">请选择分类</option>
                        @include('admin.category.option',['type'=>'articles','select_id'=>old('category_id',0)])
                    </select>
                </div>
                <div class="col-xs-8">
                    <select id="select_tags" name="select_tags" class="form-control" multiple="multiple" >
                        @foreach(array_filter(explode(",",old('select_tags',''))) as $tag)
                            <option selected="selected">{{ $tag }}</option>
                        @endforeach
                    </select>
                </div>
            </div>



            <div class="row mt-20">
                <div class="col-xs-12 col-md-11">
                    <ul class="list-inline">
                        @if( Setting()->get('code_create_article') )
                            @include('theme::layout.auth_captcha')
                        @endif
                    </ul>
                </div>

                <div class="col-xs-12 col-md-1">
                    <input type="hidden" id="article_editor_content"  name="content" value="{{ old('content','') }}"  />
                    <button type="submit" class="btn btn-primary pull-right editor-submit">上传资料</button>
                </div>
            </div>
        </form>

    </div>

@endsection
@section('script')
    <script src="{{ asset('/static/js/summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('/static/js/summernote/lang/summernote-zh-CN.min.js') }}"></script>
    <script src="{{ asset('/static/js/select2/js/select2.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#article_editor').summernote({
                lang: 'zh-CN',
                height: 350,
                placeholder:'撰写文章',
                toolbar: [ {!! config('tipask.summernote.blog') !!} ],
                callbacks: {
                    onChange:function (contents, $editable) {
                        var code = $(this).summernote("code");
                        $("#article_editor_content").val(code);
                    },
                    onImageUpload: function(files) {
                        upload_editor_image(files[0],'article_editor');
                    }
                }
            });

        });
    </script>
@endsection
