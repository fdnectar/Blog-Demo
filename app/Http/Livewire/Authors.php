<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Livewire\WithPagination;

class Authors extends Component
{
    use WithPagination;
    public $name, $email, $username, $author_type, $direct_publisher;
    public $search;
    public $perPage = 4;
    public $selected_author_id;
    public $blocked = 0;
    public $updateAuthorMode = false;

    protected $listeners = [
        'resetForms',
        'deleteAuthorAction'
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetForms()
    {
        $this->name = $this->email = $this->username = $this->author_type = $this->direct_publisher = null;
        $this->resetErrorBag();
    }


    public function addAuthor()
    {
        $this->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'username'=>'required|unique:users,username|min:6|max:20',
            'author_type'=>'required',
            'direct_publisher'=>'required'
        ], [
            'author_type.required'=>'Choose author type',
            'direct_publisher.required'=>'Specify author publication access'
        ]);

        if($this->isOnline()) {
            $default_password = Random::generate(8);
            $author = new User();
            $author->name = $this->name;
            $author->email = $this->email;
            $author->username = $this->username;
            $author->password = Hash::make( $default_password);
            $author->type = $this->author_type;
            $author->direct_publish = $this->direct_publisher;
            $saved = $author->save();

            $data = array(
                'name'=>$this->name,
                'username'=>$this->username,
                'email'=>$this->email,
                'password'=>$default_password,
                'url'=> route('author.author-profile')
            );

            $author_email = $this->email;
            $author_name = $this->name;
            // dd($author_email, $author_name);

            if($saved) {
                Mail::send('new-author-email-template', $data, function($message) use ($author_email, $author_name){
                    $message->from('noreply@example.com', 'Blog-Demo');
                    $message->to($this->email, $this->name)
                            ->subject('Account creation');
                });

                $this->name = $this->email = $this->username = $this->author_type = $this->direct_publisher = null;
                $this->dispatchBrowserEvent('hide_add_author_modal');
            } else {
                echo 'error';
            }


        } else {
            dd('I am offline');
        }
    }

    public function isOnline($site = "https://youtube.com/")
    {
        if(@fopen($site, "r")) {
            return true;
        } else {
            return false;
        }
    }

    public function editAuthor($id)
    {
        $author = User::findOrFail($id);
        $this->selected_author_id = $author->id;
        $this->name = $author->name;
        $this->email = $author->email;
        $this->username = $author->username;
        $this->author_type = $author->type;
        $this->direct_publisher = $author->direct_publish;
        $this->blocked = $author->blocked;
        $this->resetErrorBag();
        $this->updateAuthorMode = true;
        $this->dispatchBrowserEvent('show_Edit_Author_Modal');
    }

    public function updateAuthor()
    {
        $this->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.$this->selected_author_id,
            'username'=>'required'
        ]);

        if($this->selected_author_id) {
            $author = User::find($this->selected_author_id);
            $author->update([
                'name'=>$this->name,
                'email'=>$this->email,
                'username'=>$this->username,
                'username'=>$this->username,
                'type'=>$this->author_type,
                'direct_publish'=>$this->direct_publisher,
                'blocked'=>$this->blocked
            ]);

            $this->updateAuthorMode = false;
            return redirect()->route('author.authors')->with('success', 'Author updated successfully.');   
        }
    }

    public function deleteAuthor($author)
    {
        // dd('Delete Author', $author);
        $this->dispatchBrowserEvent('deleteAuthor',[
            'title' => 'Are you sure?',
            'html' =>  'You want to delete this author: <br><b>'.$author['name'].'</b>',
            'id' => $author['id']
        ]);
    }

    public function deleteAuthorAction($id)
    {
        // dd('Yes Delete');
        $author = User::find($id);
        $path = '/back/dist/img/author/';
        $author_picture = $author->getAttributes()['picture'];
        $picture_full_path = $path.$author_picture;

        if($author_picture != null || File::exists(public_path($picture_full_path))) {
            File::delete(public_path($picture_full_path)); 
        }
        $author->delete();
        
    }

    public function render()
    {
        return view('livewire.authors', [
            'authors'=>User::search(trim($this->search))
                     ->where('id', '!=', auth()->id())->paginate($this->perPage),
        ]);
    }
}
