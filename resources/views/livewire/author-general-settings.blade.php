<div>
    @if(Session::get('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
    @endif
    @if(Session::get('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif
    <form action="" wire:submit.prevent="UpdateGeneralSettings()" method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="" class="form-label">Blog Name</label>
                    <input type="text" class="form-control" placeholder="Enter Blog Name" wire:model="blog_name">
                    @error('blog_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Blog Email</label>
                    <input type="text" class="form-control" placeholder="Enter Blog Email" wire:model="blog_email">
                    @error('blog_email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Blog Description</label>
                    <textarea name="" id="" cols="3" rows="3" class="form-control" wire:model="blog_description"></textarea>
                    @error('blog_description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </form>
</div>
