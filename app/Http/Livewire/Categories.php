<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use App\Models\Post;

class Categories extends Component
{
    public $category_name;
    public $selected_category_id;
    public $updateCategoryMode = false;

    public $subcategory_name;
    public $parent_category = 0;
    public $selected_subcategory_id;
    public $updateSubCategoryMode = false;

    protected $listeners = [
        'resetModalForm',
        'deleteCategoryAction',
        'deleteSubCategoryAction',
        'updateCategoryOrdering'
    ];

    public function resetModalForm()
    {
        $this->resetErrorBag();
        $this->category_name = null;
        $this->subcategory_name = null;
        $this->parent_category = null;
    }

    public function addCategory()
    {
        $this->validate([
            'category_name'=>'required|unique:categories,category_name'
        ]);

        $category = new Category();
        $category->category_name = $this->category_name;
        $saved = $category->save();
        if($saved) {
            return redirect()->route('author.categories')->with('success', 'Category added successfully.');
        } else {
            return redirect()->route('author.categories')->with('error', 'Some error occured.');
        }
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        $this->selected_category_id = $category->id;
        $this->category_name =  $category->category_name;
        $this->updateCategoryMode = true;
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showEditCategoryModal');
    }

    public function updateCategory()
    {
        if($this->selected_category_id) {
            $this->validate([
                'category_name'=>'required|unique:categories,category_name,'.$this->selected_category_id,
            ]);

            $category = Category::findOrFail($this->selected_category_id);
            $category->category_name = $this->category_name;
            $updated = $category->save();
            if($updated) {
                $this->updateCategoryMode = false;
                return redirect()->route('author.categories')->with('success', 'Category updated successfully.');
            } else {
                return redirect()->route('author.categories')->with('error', 'Some error occured.');
            }
        }
    }

    public function addSubCategory()
    {
        $this->validate([
            'parent_category'=>'required',
            'subcategory_name'=>'required|unique:categories,category_name'
        ]);

        $subcategory = new SubCategory();
        $subcategory->sub_category_name = $this->subcategory_name;
        $subcategory->slug = Str::slug($this->subcategory_name);
        $subcategory->parent_category = $this->parent_category;
        $saved = $subcategory->save();
        if($saved) {
            return redirect()->route('author.categories')->with('success', 'Sub Category added successfully.');
        } else {
            return redirect()->route('author.categories')->with('error', 'Some error occured.');
        }
    }

    public function editSubCategory($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $this->selected_subcategory_id = $subcategory->id;
        $this->subcategory_name =  $subcategory->sub_category_name;
        $this->parent_category =  $subcategory->parent_category;
        $this->updateSubCategoryMode = true;
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showEditSubCategoryModal');
    }

    public function updateSubCategory()
    {
        if($this->selected_subcategory_id) {
            $this->validate([
                'parent_category'=>'required',
                'subcategory_name'=>'required|unique:categories,category_name,'.$this->selected_subcategory_id,
            ]);

            $subcategory = SubCategory::findOrFail($this->selected_subcategory_id);
            $subcategory->sub_category_name = $this->subcategory_name;
            $subcategory->slug = Str::slug($this->subcategory_name);
            $subcategory->parent_category = $this->parent_category;
            $updated = $subcategory->save();
            if($updated) {
                $this->updateSubCategoryMode = false;
                return redirect()->route('author.categories')->with('success', 'SubCategory updated successfully.');
            } else {
                return redirect()->route('author.categories')->with('error', 'Some error occured.');
            }
        }
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        $this->dispatchBrowserEvent('deleteCategory', [
          'title' => 'Are you sure',
          'html' => 'You want to delete this <b>'.$category->category_name.'</b> post',
          'id' => $id
        ]);
    }

    public function deleteCategoryAction($id)
    {
        $category = Category::where('id', $id)->first();
        $subcategories = SubCategory::where('parent_category', $category->id)->whereHas('posts')->with('posts')->get();

        if(!empty($subcategories) && count($subcategories) > 0) {
            $totalPosts = 0;
            foreach($subcategories as $subcat) {
                $totalPosts += Post::where('category_id', $subcat->id)->get()->count(); 
            }
            //message for not deleting category because it has post
            
        } else {
            SubCategory::where('parent_category', $category->id)->delete();
            $category->delete();
            
        }
    }

    public function deleteSubCategory($id)
    {
        $category = SubCategory::find($id);
        $this->dispatchBrowserEvent('deleteSubCategory', [
          'title' => 'Are you sure',
          'html' => 'You want to delete this <b>'.$category->sub_category_name.'</b> post',
          'id' => $id
        ]);
    }

    public function deleteSubCategoryAction($id)
    {
        $subcategory = SubCategory::where('id', $id)->first();
        $post = Post::where('category_id', $subcategory->id)->get()->toArray();

        if(!empty($post) && count($post) > 0 ) {
            //this will not delete subcategory beacuse it has related post
        } else {
            $subcategory->delete();
        }
    }

    public function updateCategoryOrdering($positions)
    {
        // dd($positions);
        // foreach($positions as $position) {
        //     $index = $position[0];
        //     $newPosition = $position[1];
        //     Category::where('id', $index)->update([
        //         'ordering'=>$newPosition,
        //     ]);
        // }
    }

    public function render()
    {
        return view('livewire.categories',[
            'categories'=>Category::orderBy('ordering', 'asc')->get(),
            'subcategories'=>SubCategory::orderBy('ordering', 'asc')->get()
        ]);
    }
}
