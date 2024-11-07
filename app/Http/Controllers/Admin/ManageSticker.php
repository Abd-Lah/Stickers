<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Sticker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ManageSticker extends Controller
{
    //
    public function index()
    {
        $products = Sticker::with('category')->orderBy('stickers.created_at', 'desc')->paginate(6);
        return view('admin.product.product-list', compact('products'));
    }

    public function create()
    {
        return view('admin.product.add-new-product', ['categories' => Category::all()]);
    }

    public function store(Request $request)
    {

        try{
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'caracteristics' => 'array', // Adjust this based on your database structure
                'caracteristics.*' => 'string',
                'price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0|max:100',
                'image' => 'array',
                'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate images if necessary
            ]);
            $data = $request->only(['category_id', 'name', 'description', 'price', 'discount', 'caracteristics']);
            $data['slug'] = Str::slug($data['name']);
            // Add images to storage
            $data['image'] = [];
            // Handle uploaded files
            foreach ($request->file('image') as $image) {
                // Generate a unique filename
                $filename = $data['slug'] . rand(0, 1e10) . '.' . $image->getClientOriginalExtension();
                // Store the file in the public disk
                $image->storeAs('stickers-image', $filename, 'public');
                // Collect filenames
                $data['image'][] = $filename;
            }
            Sticker::create($data);
            return response()->json([
                'status_code' => 200,
                'message' => "Sticker created successfully.",
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status_code' => 422,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ]);
        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function show($slug){
        try{
            $sticker = Sticker::where('slug', $slug)->firstOrFail();
            if($sticker){
                return view('admin.product.add-new-product', ['product' => $sticker, 'categories' => Category::all()]);
            }else{
                return redirect()->back()->with('error', 'Sticker not found.');
            }
        }catch (\Exception $exception){
            return redirect()->back()->with('error', 'Internal server error.');
        }
    }

    public function update(Request $request, $slug)
    {
        try {
            $sticker = Sticker::where('slug', $slug)->firstOrFail();

            // Validate incoming request
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => [
                    'required',
                    'string',
                    'min:3',
                    Rule::unique('categories')->ignore($sticker->id),
                    'max:60'
                ],
                'description' => 'required|string',
                'caracteristics' => 'array',
                'caracteristics.*' => 'string',
                'price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0|max:100',
                'image' => 'array',
                'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate images if necessary
                'removed_images' => 'array', // Array of images to remove
            ]);


            // Prepare the data for updating
            $data = $request->only(['category_id', 'name', 'description', 'price', 'discount']);
            $data['slug'] = Str::slug($data['name']);

            // Handle the images
            $imagesToDelete = $request->input('removed_images', []); // Get images to delete
            $currentImages = $sticker->image; // Get current images
            $data['image'] = []; // Array to hold new images

            // Loop through current images to handle deletion and new uploads
            foreach ($currentImages as $currentImage) {
                if (in_array($currentImage, $imagesToDelete)) {
                    // If the current image is marked for deletion, delete it from storage
                    $filePath = 'stickers-image/' . $currentImage;
                    if (Storage::exists($filePath)) {
                        Storage::delete($filePath);
                    }
                } else {
                    // If not deleted, keep it in the new images array
                     $data['image'][] = $currentImage;
                }
            }

            // Handle uploaded files
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    // Generate a unique filename
                    $filename = $data['slug'] . rand(0, 1e10) . '.' . $image->getClientOriginalExtension();

                    // Store the file in the public disk
                    $image->storeAs('stickers-image', $filename, 'public');

                    // Add the new image to the new images array
                     $data['image'][] = $filename; // Add new image to the array
                }
            }
            $data['caracteristics'] = $request->input('caracteristics', []);
            $sticker->update($data);

            return response()->json([
                'status_code' => 200,
                'message' => "Sticker updated successfully.",
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status_code' => 422,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ]);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $sticker = Sticker::findOrfail($id);
            if ($sticker->image) {
                foreach ($sticker->image as $image) {
                    $filePath = 'stickers-image/' . $image;
                    if (Storage::exists($filePath)) {
                        Storage::delete($filePath);
                    }
                }
            }
            $sticker->delete();
            return redirect()->back()->with('success', 'Sticker deleted successfully.');
        }catch (\Exception $exception){
            return redirect()->back()->with('error', 'Internal server error.');
        }

    }
}
