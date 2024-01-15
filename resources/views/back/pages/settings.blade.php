@extends('back.layouts.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Settings')

@section('content')

<div class="row align-items-center">
    <div class="col">
        <h2 class="page-title">Settings</h2>
    </div>
</div>

<div class="card mt-5">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="#tabs-home-8" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">General Settings</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#tabs-profile-8" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Logs & Favicon</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#tabs-activity-8" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Social Network</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade active show" id="tabs-home-8" role="tabpanel">
                <div>
                    @livewire('author-general-settings')
                </div>
            </div>
            <div class="tab-pane fade" id="tabs-profile-8" role="tabpanel">
                <div>
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Set Blog Logo</h3>
                            <div class="mb-2" style="max-width:200px;">
                                <img src="" class="img-thumbnail" id="logo-image-preview" alt="">
                            </div>
                            <form action="{{ route('author.change-blog-logo') }}" id="changeBlogLogo" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-2">
                                    <input type="file" name="blog_logo" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tabs-activity-8" role="tabpanel">
                    <h4>Activity tab</h4>
                <div>
                    Donec ac vitae diam amet vel leo egestas consequat rhoncus in luctus amet, facilisi sit mauris accumsan nibh habitant senectus
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-scripts')
    <script>
        $('#changeBlogLogo').submit(function(e){
            e.preventDefault();
            var form = this;
            $.ajax({
                url:$(form).attr('action'),
                method:$(form).attr('method'),
                data:new FormData(form),
                processData:false,
                dataType:'json',
                contentType:false,
                beforeSend:function(){},
                success:function(data) {
                    if(data.status == 1) {
                        alert('Success! Response: ' + data.message)
                        $(form)[0].reset();
                    } else {
                        alert('Error! Response: ' + data.error)
                    }
                }
            });
        });
    </script>
@endpush