<div>
    
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Authors</h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="d-flex">
                        <input type="search" class="form-control d-inline-block w-9 me-3" placeholder="Search author…"  wire:model="search">
                        <a href="#" class="btn btn-primary" data-bs-target='#add_author_modal' data-bs-toggle='modal'>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                            New Author
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row row-cards">
        @forelse($authors as $author)
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body p-4 text-center">
                    <span class="avatar avatar-xl mb-3 rounded" style="background-image: url({{ $author->picture }})"></span>
                    <h3 class="m-0 mb-1"><a href="#">{{ $author->name }}</a></h3>
                    <div class="text-muted">{{ $author->email }}</div>
                    <!-- <div class="mt-3">
                        <span class="badge bg-purple-lt">Owner</span>
                    </div> -->
                </div>
                <div class="d-flex">
                    <a href="#" wire:click.prevent="editAuthor({{ $author->id}})" class="card-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                        Edit
                    </a>
                    <a href="#" wire:click.prevent="deleteAuthor({{ $author }})" class="card-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                        Delete
                    </a>
                </div>
            </div>
        </div>
        @empty
            <span class="text-danger">No User Found...</span>
        @endforelse
    </div>

    <div class="row mt-4">
        {{ $authors->links('livewire::simple-bootstrap') }}
    </div>



    <!-- Modals -->

    <div wire:ignore.self class="modal modal-blur fade" id="add_author_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $updateAuthorMode ? 'Update Author' : 'Add Author' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST"
                    @if($updateAuthorMode)
                        wire:submit.prevent='updateAuthor()'
                    @else
                        wire:submit.prevent='addAuthor()'
                    @endif
                    >
                    <div class="modal-body">
                        @if($updateAuthorMode)
                            <input type="hidden" wire:model="selected_author_id">
                        @endif
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" placeholder="Enter Name" wire:model="name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" placeholder="Enter Email" wire:model="email">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" placeholder="Enter Username" wire:model="username">
                            @error('username')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Author Type</label>
                            <div>
                                <select class="form-select" wire:model="author_type">
                                    @if(!$updateAuthorMode)
                                        <option value="">---Not Selected---</option>
                                    @endif
                                    @foreach(\App\Models\Type::all() as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('author_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-label">Is direct publisher?</div>
                                <div>
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="0" wire:model="direct_publisher">
                                        <span class="form-check-label">No</span>
                                    </label>
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="1" wire:model="direct_publisher">
                                        <span class="form-check-label">Yes</span>
                                    </label>
                                </div>
                                @error('direct_publisher')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if($updateAuthorMode)
                        <div class="mb-3 ms-4">
                            <div class="form-label">Blocked</div>
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model="blocked">
                                <span class="form-check-label"></span>
                            </label>
                        </div>
                        @endif
                        <div class="modal-footer">
                            <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">{{ $updateAuthorMode ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- <div wire:ignore.self class="modal modal-blur fade" id="edit_author_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Author</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updateAuthor()" method="POST">
                        <input type="text" wire:model="selected_author_id">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" placeholder="Enter Name" wire:model="name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" placeholder="Enter Email" wire:model="email">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" placeholder="Enter Username" wire:model="username">
                            @error('username')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Author Type</label>
                            <div>
                                <select class="form-select" wire:model="author_type">
                                    @foreach(\App\Models\Type::all() as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('author_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-label">Is direct publisher?</div>
                                <div>
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="0" wire:model="direct_publisher">
                                        <span class="form-check-label">No</span>
                                    </label>
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="1" wire:model="direct_publisher">
                                        <span class="form-check-label">Yes</span>
                                    </label>
                                </div>
                                @error('direct_publisher')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 ms-4">
                            <div class="form-label">Blocked</div>
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model="blocked">
                                <span class="form-check-label"></span>
                            </label>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> -->

</div>
