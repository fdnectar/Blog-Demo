@extends('front.layouts.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Welcome to Blog Demo')
@section('meta_tags')
    <meta name="robots" content="index,follow"/>
    <meta name="title" content="{{ blogInfo()->blog_name }}"/>
    <meta name="description" content="{{ blogInfo()->blog_description }}"/>
    <meta name="author" content="{{ blogInfo()->blog_name }}"/>
    <link rel="canonical" href="{{ Request::root() }}">
    <meta property="og:title" content="{{ blogInfo()->blog_name }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="{{ blogInfo()->blog_description }}"/>
    <meta property="og:url" content="{{ Request::root() }}"/>
    <meta property="og:image" content="{{ blogInfo()->blog_logo }}"/>
    <meta name="twitter:domain" content="{{ Request::root() }}"/>
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:title" property="og:title" itemprop="name" content="{{ blogInfo()->blog_name }}"/>
    <meta name="twitter:description" property="og:description" itemprop="description" content="{{ blogInfo()->blog_description }}"/>
@endsection
@section('content')

    <div class="row no-gutters-lg">
        <div class="col-12">
            <h2 class="section-title">Latest Articles</h2>
        </div>
        <div class="col-lg-8 mb-5 mb-lg-0">
            <div class="row">
                <div class="col-12 mb-4">
                    @if(single_latest_post())
                        <article class="card article-card">
                            <a href="{{ route('read_post', single_latest_post()->post_slug) }}">
                                <div class="card-image">
                                    <div class="post-info"> <span class="text-uppercase">{{ date_formatter(single_latest_post()->created_at) }}</span>
                                        <span class="text-uppercase">{{ readDuration(single_latest_post()->post_title, single_latest_post()->post_content) }} @choice('min|mins', readDuration(single_latest_post()->post_title, single_latest_post()->post_content)) read</span>
                                    </div>
                                    <img loading="lazy" decoding="async" src="/storage/images/post_images/{{ single_latest_post()->featured_image }}" alt="Post Thumbnail" class="w-100">
                                </div>
                            </a>
                            <div class="card-body px-0 pb-1">
                                <!-- <ul class="post-meta mb-2">
                                    <li> <a href="#!">travel</a> <a href="#!">news</a></li>
                                </ul> -->
                                <h2 class="h1"><a class="post-title" href="{{ route('read_post', single_latest_post()->post_slug) }}">{{ single_latest_post()->post_title }}</a></h2>
                                <p class="card-text">{!! Str::ucfirst(words(single_latest_post()->post_content, 25)) !!}</p>
                                <div class="content"><a class="read-more-btn" href="{{ route('read_post', single_latest_post()->post_slug) }}">Read Full Article</a></div>
                            </div>
                        </article>
                    @endif
                </div>
                @foreach(latest_home_6post() as $value)
                    <div class="col-md-6 mb-4">
                        <article class="card article-card article-card-sm h-100">
                            <a href="{{ route('read_post', $value->post_slug) }}">
                                <div class="card-image">
                                    <div class="post-info"> <span class="text-uppercase">{{ date_formatter($value->created_at) }}</span>
                                        <span class="text-uppercase">{{ readDuration($value->post_title, $value->post_content) }} @choice('min|mins', readDuration($value->post_title, $value->post_content)) read</span>
                                    </div>
                                    <img loading="lazy" decoding="async" src="/storage/images/post_images/thumbnails/resized_{{ $value->featured_image }}" alt="Post Thumbnail" class="w-100">
                                </div>
                            </a>
                            <div class="card-body px-0 pb-0">
                                <ul class="post-meta mb-2">
                                    <li><a href="{{ route('category_posts', $value->subcategory->slug) }}">{{ $value->subcategory->sub_category_name }}</a></li>
                                </ul>
                                <h2><a class="post-title" href="{{ route('read_post', $value->post_slug) }}">{{ $value->post_title }}</a></h2>
                                <p class="card-text">{!! Str::ucfirst(words($value->post_content, 15)) !!}</p>
                                <div class="content"><a class="read-more-btn" href="{{ route('read_post', $value->post_slug) }}">Read Full Article</a></div>
                            </div>
                        </article>
                    </div>
                @endforeach
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
                                <p class="mb-3 pb-2">Hello, I’m Hootan Safiyari. A Content writter, Developer and Story teller. Working as a Content writter at CoolTech Agency. Quam nihil …</p>
                                <a href="about.html" class="btn btn-sm btn-outline-primary">Know More</a>
                            </div>
                        </div>
                    </div>
                    @if(recommended_post())
                        <div class="col-lg-12 col-md-6">
                            <div class="widget">
                                <h2 class="section-title mb-3">Recommended</h2>
                                <div class="widget-body">
                                    <div class="widget-list">
                                        @foreach(recommended_post() as $value)
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
                </div>
            </div>
        </div>
    </div>

@endsection