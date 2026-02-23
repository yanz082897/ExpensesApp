<?php

namespace App\Livewire;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Category - ExpenseApp")]
class Categories extends Component
{
    public $name = "";
    public $color = "#3B82F6";
    public $icon = "";
    public $editingId = null;
    public $isEditing = false;
    public $colors = [
        '#EF4444', // Red
        '#F97316', // Orange
        '#F59E0B', // Amber
        '#EAB308', // Yellow
        '#84CC16', // Lime
        '#22C55E', // Green
        '#10B981', // Emerald
        '#14B8A6', // Teal
        '#06B6D4', // Cyan
        '#0EA5E9', // Sky
        '#3B82F6', // Blue
        '#6366F1', // Indigo
        '#8B5CF6', // Violet
        '#A855F7', // Purple
        '#D946EF', // Fuchsia
        '#EC4899', // Pink
        '#F43F5E', // Rose
    ];
    #[Computed]
    public function categories() {
        return Category::withCount('expenses')
            ->where('user_id',Auth::id())
            ->orderBy('name')
            ->get();
    }
    public function rules(){
        return[
             'name' => 'required|string|max:255|unique:categories,name,' . ($this->editingId ?: 'NULL') . ',id,user_id,' . Auth::id(),
            'color' => 'required|string',
            'icon' => 'nullable|string|max:255',
        ];
    }
    public function delete($categoryId){
        $category = Category::findOrFail($categoryId);
        if($category && $category->user_id !== Auth::id()){
            abort(403, 'Unauthorized');
        }
        if($category->expenses()->count() > 0){
            session()->flash('error', 'Cannot delete category with associated expenses. Please reassign or delete the expenses first.');
            return;
        }
        $category->delete();
        session()->flash('message','Category deleted successfully!');

    }
    public function cancelEdit(){
        $this->reset(['name', 'color', 'icon', 'editingId', 'isEditing']);
        $this->color = "#3B82F6";
    }
    public function edit($categoryId){
        $category=Category::findOrFail($categoryId);
        if($category && $category->user_id !== Auth::id()){
            abort(403, 'Unauthorized');
        }
            $this->name = $category->name;
            $this->color = $category->color;
            $this->icon = $category->icon;
            $this->editingId = $categoryId;
            $this->isEditing = true;
    }
    public function save(){
        $this->validate();

        if($this->isEditing && $this->editingId)
        {
            $category = Category::findOrFail($this->editingId);
            $category->update([
                'name' => $this->name,
                'color' => $this->color,
                'icon' => $this->icon,
            ]);

            session()->flash('message','Category updated successfully!');
            $this->cancelEdit();

        }else{
                Category::create(
                    [
                    'name' => $this->name,
                    'color' => $this->color,
                    'icon' => $this->icon,
                    'user_id' => Auth::id()
                ]
            );
            session()->flash('message','Category created successfully!');
            $this->reset(['name', 'color', 'icon']);
        }
    }

    protected $messages = [
        'name.required' => 'Please enter a category name.',
        'name.unique' => 'You already have a category with this name.',
        'color.required' => 'Please select a color.',
    ];


    public function render()
    {
        return view('livewire.categories', ['categories' => $this->categories]);
    }
}
