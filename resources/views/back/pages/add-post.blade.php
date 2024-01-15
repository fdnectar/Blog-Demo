@extends('back.layouts.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Add New Post')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Add New Post</h2>
            </div>
        </div>
    </div>
</div>
<hr>

<form action="{{route('author.posts.create-post')}}" method="POST" id="addPostForm" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <div class="mb-3">
                        <label class="form-label">Post Title</label>
                        <input type="text" class="form-control" name="post_title" placeholder="Post title">
                        <span class="text-danger error-text post_title_error"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Post Content</label>
                        <textarea class="ckeditor form-control" name="post_content" rows="6" placeholder="Content.." id="post_content"></textarea>
                        <span class="text-danger error-text post_content_error"></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-label">Select Category</div>
                        <select class="form-select" name="post_category">
                            <option value="">No Selected</option>
                            @foreach(\App\Models\SubCategory::all() as $category)
                                <option value="{{ $category->id }}">{{ $category->sub_category_name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text post_category_error"></span>
                    </div>
                    <div class="mb-3">
                        <div class="form-label">Featured Image</div>
                        <input type="file" class="form-control" name="featured_image">
                        <span class="text-danger error-text featured_image_error"></span>
                    </div>
                    <!-- <div class="image_holder mb-2" style="max-width: 250px;">
                        <img src="" alt="" class="img-thumbnail" id="image-previewer" data-ijabo-default-img=''>
                    </div> -->
                    <div class="mb-3">
                        <label for="" class="form-label">Post Tags</label>
                        <input type="text" name="post_tags" class="form-control">
                    </div>
                    <button type="submi" class="btn btn-primary">Save Post</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('custom-scripts')

    <script src="/ckeditor/ckeditor.js"></script>

    <script>

        

        $(function(){
            $('#addPostForm').on('submit', function(e){
                e.preventDefault();
                var post_content = CKEDITOR.instances.post_content.getData();
                var form = this;
                var formData = new FormData(form);
                    formData.append('post_content', post_content);

                $.ajax({
                    url:$(form).attr('action'),
                    method:$(form).attr('method'),
                    data:formData,
                    processData:false,
                    dataType:'json',
                    contentType:false,
                    beforeSend:function() {
                        $(form).find('span.error-text').text('');
                    },
                    success:function(response) {
                        if(response.code == 1){
                            $(form)[0].reset();
                            CKEDITOR.instances.post_content.setData('');
                            $('input[name="post_tags"]').amsifySuggestags();
                            alert('Post added Successfully');
                        } else {
                            alert('something went wrong');
                        }
                    },
                    error:function(response) {
                        $.each(response.responseJSON.errors, function(prefix,val){
                            $(form).find('span.'+prefix+'_error').text(val[0]);
                        });
                    },
                });
            });
        });
    </script>
@endpush