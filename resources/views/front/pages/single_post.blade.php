@extends('front.layouts.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Welcome to Blog Demo')

@section('meta_tags')
<meta name="title" content="{{ $post->post_title }}"/>
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="description" content="{{ Str::ucfirst(words($post->post_content,120)) }}">
<meta name="author" content="{{ $post->author->username }}">
<link rel="canonical" href="{{ route('read_post', $post->post_slug) }}">
<meta property="og:title" content="{{ Str::ucfirst($post->post_title) }}"></meta>
<meta property="og:type" content="article"></meta>
<meta property="og:description" content="{{ Str::ucfirst(words($post->post_content,120)) }}"></meta>
<meta property="og:url" content="{{ route('read_post', $post->post_slug) }}"></meta>
<meta property="og:image" content="/storage/images/post_images/thumbnails/resized_{{ $post->featured_image }}"></meta>
<meta name="twitter:domain" content="{{ Request::getHost() }}">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{ Str::ucfirst(words($post->post_content,120)) }}">
<meta name="twitter:image" content="/storage/images/post_images/thumbnails/resized_{{ $post->featured_image }}">

@endsection

@section('content')

<div class="row">
	<div class="col-lg-8 mb-5 mb-lg-0">
        <article>
            <img loading="lazy" decoding="async" src="/storage/images/post_images/{{ $post->featured_image }}" alt="Post Thumbnail" class="w-100">
            <ul class="post-meta mb-2 mt-4">
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" style="margin-right:5px;margin-top:-4px" class="text-dark" viewBox="0 0 16 16">
                        <path d="M5.5 10.5A.5.5 0 0 1 6 10h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5z"></path>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z"></path>
                        <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4z"></path>
                    </svg> <span>{{ date_formatter($post->created_at) }}</span>
                </li>
            </ul>
            <h1 class="my-3">{{ $post->post_title }}</h1>
            <ul class="post-meta mb-4">
                <li> <a href="{{ route('category_posts', $post->subcategory->slug) }}">{{ $post->subcategory->sub_category_name }}</a></li>
            </ul>
            <div class="content text-left">
                <p>{!! $post->post_content !!}</p>
            </div>
            @if( $post->post_tags )
                @php
                    $tagsString = $post->post_tags;
                    $tagsArray = explode(',', $tagsString)
                @endphp
                <div class="tags-container mt-4">
                    <ul class="post-meta">
                        @foreach($tagsArray as $tag)
                            <li><a href="{{ route('tag_posts', $tag) }}">#{{ $tag }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </article>
        @if(count($related_posts) > 0)
            <div class="widget mt-5">
                <h2 class="section-title mb-3">Related Post</h2>
                <div class="widget-body">
                    <div class="widget-list">
                        @foreach($related_posts as $value)
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
        @endif
        <div class="mt-5">
            <div id="disqus_thread"></div>
                <script>
                    /**
                    *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                    *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables    */
                    
                    var disqus_config = function () {
                    this.page.url = "{{ route('read_post', $post->post_slug) }}";  // Replace PAGE_URL with your page's canonical URL variable
                    this.page.identifier = "{{ $post->id }}"; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                    };
                    
                    (function() { // DON'T EDIT BELOW THIS LINE
                    var d = document, s = d.createElement('script');
                    s.src = 'https://blog-demo-6.disqus.com/embed.js';
                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                    })();
                </script>
                <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
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
                                    @foreach(sidebar_latest_post($post->id) as $value)
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

@push('stylesheets')
    <link rel="stylesheet" href="/social_buttons/jquery.floating-social-share.min.css">
@endpush

@push('custom-scripts-front')
    <script src="/social_buttons/jquery.floating-social-share.min.js"></script>

    <script>
        $(function(){
            $("body").floatingSocialShare({
                buttons:[
                    "facebook", "linkedin", "twitter", "telegram"
                ],
                text:"share with: ",
                url:"{{ route('read_post', $post->post_slug) }}"
            });
        });
    </script>
@endpush