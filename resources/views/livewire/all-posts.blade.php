<div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="" class="form-label">Search</label>
            <input type="text" class="form-control" placeholder="keyword..." wire:model="search">
        </div>
        
        <div class="col-md-2 mb-3">
            <label for="" class="form-label">Category</label>
            <select wire:model="category" class="form-select">
                <option value="">--No Selected--</option>
                @foreach(\App\Models\SubCategory::whereHas('posts')->get() as $category)
                    <option value="{{ $category->id }}">{{ $category->sub_category_name }}</option>
                @endforeach
            </select>
        </div>

        @if(Auth()->user()->type == 1)
            <div class="col-md-2 mb-3">
                <label for="" class="form-label">Author</label>
                <select class="form-select" wire:model="author">
                    <option value="">--No Selected--</option>
                    @foreach(\App\Models\User::whereHas('posts')->get() as $author)
                        <option value="{{ $author->id }}">{{ $author->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="col-md-2 mb-3">
            <label for="" class="form-label">OrderBy</label>
            <select class="form-select" wire:model="orderBy">
                <option value="asc">Asc</option>
                <option value="desc">Desc</option>
            </select>
        </div>
    </div>
    

    <div class="row row-cards">
        @forelse($posts as $post)
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <img src="/storage/images/post_images/thumbnails/resized_{{ $post->featured_image }}" alt="" class="card-img-top">
                    <div class="card-body p-2">
                        <h3 class="m-0 mb-1">{{ $post->post_title }}</h3>
                    </div>
                    <div class="d-flex">
                        <a href="{{ route('author.posts.edit-post', ['post_id'=>$post->id]) }}" class="card-btn">Edit</a>
                        <a href="" wire:click.prevent="deletePost({{ $post->id }})" class="card-btn">Delete</a>
                    </div>
                </div>
            </div>
        @empty
        <span class="text-danger">No Post(s) Found..</span>    
        @endforelse
    </div>

    <div class="d-block mt-2">
        {{ $posts->links('livewire::simple-bootstrap') }}
    </div>


</div>
