<div>
    @if(Session::get('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif
    @if(Session::get('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
     @endif
    <div class="row mt-3">
        <div class="col-md-6 mb-2">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills card-header-pills">
                        <h4>Category</h4>
                        <li class="nav-item ms-auto">
                            <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#categories_modal">Add Category</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                <th>Category Name</th>
                                <th>N. of Subcategory</th>
                                <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody id="sortable_category">
                                @forelse($categories as $category)
                                    <tr data-index="{{ $category->id }}" data-ordering="{{ $category->ordering }}">
                                        <td>{{ $category->category_name }}</td>
                                        <td class="text-muted">{{ $category->subCategories->count() }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-sm btn-primary" wire:click.prevent="editCategory({{$category->id}})">Edit</a> &nbsp; 
                                                <a href="#" class="btn btn-sm btn-danger" wire:click.prevent="deleteCategory({{ $category->id }})">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3"><span class="text-danger">No Category Found</span></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills card-header-pills">
                        <h4>SubCategory</h4>
                        <li class="nav-item ms-auto">
                            <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#sub_categories_modal">Add SubCategory</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                <th>SubCategory Name</th>
                                <th>Parent Categroy</th>
                                <th>N. of post</th>
                                <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subcategories as $value)
                                    <tr>
                                        <td>{{$value->sub_category_name}}</td>
                                        <td>{{$value->parent_category != 0 ? $value->parentcategory->category_name : ' - '}}</td>
                                        <td class="text-muted">{{ $value->posts->count() }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-sm btn-primary" wire:click.prevent="editSubCategory({{$value->id}})">Edit</a> &nbsp; 
                                                <a href="#" class="btn btn-sm btn-danger" wire:click.prevent="deleteSubCategory({{ $value->id }})">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3"><span class="text-danger">No Sub Category Found</span></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->

    <!-- category modal -->
    <div wire:ignore.self class="modal modal-blur fade" id="categories_modal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $updateCategoryMode ? 'Update Category' : 'Add Category' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-content" action="" mehtod="POST"
                    @if($updateCategoryMode)
                        wire:submit.prevent='updateCategory()'
                    @else
                        wire:submit.prevent='addCategory'
                    @endif
                    >
                    <div class="modal-body">  
                        @if($updateCategoryMode)
                            <input type="hidden" wire:model="selected_category_id">
                        @endif    
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" class="form-control" placeholder="Category Name" wire:model="category_name">
                            @error('category_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-footer" style="border-top:none;">
                            <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">{{ $updateCategoryMode ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end-category modal -->

    <div wire:ignore.self class="modal modal-blur fade" id="sub_categories_modal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $updateSubCategoryMode ? 'Update SubCategory' : 'Add SubCategory' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" mehtod="POST"
                    @if($updateSubCategoryMode)
                        wire:submit.prevent='updateSubCategory()'
                    @else
                        wire:submit.prevent='addSubCategory'
                    @endif
                    >
                    <div class="modal-body">
                        @if($updateSubCategoryMode)
                            <input type="hidden" wire:model="selected_subcategory_id">
                        @endif 
                        <div class="mb-3">
                            <div class="form-label">Parent Category</div>
                            <select class="form-select" wire:model="parent_category">
                                <option value="0">---UnCategorized---</option>
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{$category->id}}">{{$category->category_name}}</option>
                                @endforeach
                            </select>
                            @error('parent_category')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">SubCategory Name</label>
                            <input type="text" class="form-control" name="example-text-input" placeholder="SubCategory Name" wire:model="subcategory_name">
                            @error('subcategory_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-footer" style="border-top:none;">
                            <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">{{ $updateSubCategoryMode ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- End Modals -->
</div>
