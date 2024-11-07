<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Nette\Schema\ValidationException;

class ManageCategory extends Controller
{
    //
    public function index()
    {
        $categories = Category::withCount('stickers')
            ->orderBy('created_at', 'desc')
            ->paginate(6);
        return view('admin.category.category-list',['categories'=>$categories]);
    }
    public function create()
    {
        return view('admin.category.add-new-category');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|unique:categories|max:60',
            'description' => 'required|string|min:20',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        try {
            // Validate the input
            $data = $request->only(['name', 'description']);
            $data['slug'] = Str::slug($data['name']);

            // Handle file upload
            if ($request->hasFile('image')) {
                $filename = $data['slug'] . '-' . rand(0, 1e10) . '.' . $request->file('image')->getClientOriginalExtension();
                // Store the image in the 'public' disk
                $request->file('image')->storeAs('categories-image', $filename, 'public');
                $data['image'] = $filename;
            }

            // Save the category to the database
            Category::create($data);

            // Redirect with success message
            return redirect()->route('category-list')->with('success', 'Category created successfully.');
        }  catch (\Exception $exception) {
            // Handle any other errors
            return redirect()->back()->with('error', 'Something went wrong! Try again.');
        }
    }

    public function show($slug)
    {
        $category = Category::where('slug',$slug)->with(['stickers'])->first();
        if(!$category){
            return redirect()->route('category-list')->with('error', 'Category not found.');
        }else{
            return view('admin.category.category-show',['category'=>$category]);
        }
    }

    public function update(Request $request, $slug)
    {
        //
        $category = Category::where('slug',$slug)->first();
        if(!$category){
            return redirect()->route('category-list')->with('error', 'Category not found.');
        }else{
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'min:3',
                    Rule::unique('categories')->ignore($category->id),
                    'max:60'
                ],
                'description' => 'required|string|min:20',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        }
        try {
            // Validate the input
            $new_data = $request->only(['name', 'description']);
            $new_data['slug'] = Str::slug($new_data['name']);

            // Handle file upload
            if ($request->hasFile('image')) {
                Storage::delete('categories-image/' . $category->image);
                $filename = $new_data['slug'] . '-' . rand(0, 1e10) . '.' . $request->file('image')->getClientOriginalExtension();
                // Store the image in the 'public' disk
                $request->file('image')->storeAs('categories-image', $filename, 'public');
                $new_data['image'] = $filename;
            }

            // Save the category to the database
            $category->update($new_data);
            // Redirect with success message
            return redirect()->route('category-list')->with('success', 'Category updated successfully.');
        }  catch (\Exception $exception) {
            // Handle any other errors
            return redirect()->back()->with('error', 'Something went wrong! Try again.');
        }

    }

    public function destroy($id){
        try {
            $category = Category::findOrFail($id);
            if($category){
                $filePath = 'categories-image/' . $category->image; // Assuming 'image' holds the filename
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
                $category->delete();
                return redirect()->route('category-list')->with('success', "Category $category->name deleted successfully.");
            }else{
                return redirect()->route('category-list')->with('error', "Category not found.");
            }
        }catch (\Exception $exception){
            return redirect()->back()->with('error', 'Something went wrong! Try again.');
        }

    }
}
