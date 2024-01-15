@extends('front.layouts.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Welcome to Blog Demo')

@section('content')

<div class="row">
    <div class="col-12">
        <h1 class="mb-4 border-bottom border-primary d-inline-block">{{ $pageTitle }}</h1>
    </div>
    <div class="col-lg-8 mb-5 mb-lg-0">
        <div class="row">
            @forelse($posts as $value)
                <div class="col-md-6 mb-4">
                    <article class="card article-card article-card-sm h-100">
                        <a href="{{ route('read_post', $value->post_slug) }}">
                            <div class="card-image">
                                <div class="post-info"> <span class="text-uppercase">{{ date_formatter($value->created_at) }}</span>
                                    <span class="text-uppercase">{{ readDuration($value->post_title, $value->post_content) }} @choice('min|mins', readDuration($value->post_title, $value->post_content)) read</span>
                                </div>
                                <img loading="lazy" decoding="async" src="/storage/images/post_images/thumbnails/resized_{{ $value->featured_image }}" alt="Post Thumbnail" class="w-100" width="420" height="280">
                            </div>
                        </a>
                        <div class="card-body px-0 pb-0">
                            <h2><a class="post-title" href="{{ route('read_post', $value->post_slug) }}">{{ $value->post_title }}</a></h2>
                            <p class="card-text">{!! Str::ucfirst(words($value->post_content, 15)) !!}</p>
                            <div class="content"> <a class="read-more-btn" href="{{ route('read_post', $value->post_slug) }}">Read Full Article</a>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <span class="text-danger">No post(s) found for this category!</span>
            @endforelse
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    {{ $posts->appends(request()->input())->links('custom_pagination') }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="widget-blocks">
            <div class="row">
                <div class="col-lg-12">
                    <div class="widget">
                        <div class="widget-body">
                            <img loading="lazy" decoding="async" src="/front/images/author.jpg" alt="About Me" class="w-100 author-thumb-sm d-block">
                            <h2 class="widget-title my-3">Hootan Safiyari</h2>
                            <p class="mb-3 pb-2">Hello, I’m Hootan Safiyari. A Content writter, Developer and Story teller. Working as a Content writter at CoolTech Agency. Quam nihil …</p> <a href="about.html" class="btn btn-sm btn-outline-primary">Know
                            More</a>
                        </div>
                    </div>
                </div>
                @if(sidebar_latest_post())
                    <div class="col-lg-12 col-md-6">
                        <div class="widget">
                            <h2 class="section-title mb-3">Latest Post</h2>
                            <div class="widget-body">
                                <div class="widget-list">
                                    @foreach(sidebar_latest_post() as $value)
                                        <a class="media align-items-center" href="{{ route('read_post', $value->post_slug) }}">
                                            <img loading="lazy" decoding="async" src="/storage/images/post_images/thumbnails/thumb_{{ $value->featured_image }}" alt="Post Thumbnail" class="w-100">
                                            <div class="media-body ml-3">
                                                <h3 style="margin-top:-5px">{{ $value->post_title }}</h3>
                                                <p class="mb-0 small">{!! Str::ucfirst(words($value->post_content, 10)) !!}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(categories())
                    <div class="col-lg-12 col-md-6">
                        <div class="widget">
                            <h2 class="section-title mb-3">Categories</h2>
                            <div class="widget-body">
                                <ul class="widget-list">
                                    @foreach(categories() as $value)
                                        <li><a href="{{ route('category_posts', $value->slug) }}">{{ Str::ucfirst($value->sub_category_name) }} <span class="ml-auto">({{ $value->posts->count() }})</span></a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if(all_tags() != null)
                    @php 
                        $allTagsString = all_tags();
                        $allTagsArray = explode(',',$allTagsString)
                    @endphp    
                    <div class="col-lg-12 col-md-6">
                        <div class="widget">
                            <h2 class="section-title mb-3">Post Tags</h2>
                            <div class="widget-body">
                                <ul class="widget-list">
                                    @foreach(array_unique($allTagsArray) as $tag)
                                        <li><a href="{{ route('tag_posts', $tag) }}">#{{ $tag }}</a></li>
                                    @endforeach
                                </ul>
                            </div>                                                                      
                        </div>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>

@endsection