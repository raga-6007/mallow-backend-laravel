<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Blog=Blog::get();
        if( $Blog){
            return response()->json(['status'=>true,'message' => $Blog], 200);
        }
        else{
            return response()->json(['status'=>false,'message' =>'Error'], 500);
        }

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            // Validate the request
            $request->validate([
                'name' => 'required',
                'detail' => 'required',
                'subDetail'=>'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg', // Include max size validation if needed
            ]);
        
            // Get all request data
            $input = $request->all();
        
            // Handle image upload if provided
            if ($image = $request->file('image')) {
                // Define destination path
                $destinationPath = 'images/'; // Folder where the images will be stored
                $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension(); // Generate unique filename
                $image->move(public_path($destinationPath), $imageName); // Move the image to the destination folder
                $input['image'] = $destinationPath . $imageName; // Store the image path in the database
            }
        
            // Try to create a new blog post
            try {
                Blog::create($input); // Create a new Blog record with the input data
        
                // Return a success response
                return response()->json(['status' => "true", 'message' => 'Blog post created successfully!'], 200);
            } catch (\Exception $e) {
                // Return an error response in case of an exception
                return response()->json(['status' => "false", 'error' => 'Failed to create blog post. ' . $e->getMessage()], 500);
            }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog,$id)
    {
        $blog = Blog::find($id);
        if ($blog) {
            return response()->json(['status'=>true,'message' => $blog], 200);
        }
        else{
        return response()->json(['status'=>false,'message' => 'Blog Not found'], 500);
        }
        
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $blog = Blog::find($id);
        if ($blog->image == $request['image']) {
            $request->validate([
                'name' => 'required|string|max:255',
                'detail' => 'required|string',
                'subDetail'=>'required',
            ]);
        }
        else{
        $request->validate([
            'name' => 'required|string|max:255',
            'detail' => 'required',
            'subDetail'=>'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional image
        ]);
       }
       
    
        if (!$blog) {
            return response()->json(['status' => false, 'error' => 'Blog post not found'], 404);
        }
    
        // Get all request data
        $input = $request->all();
    
        // Handle image upload if provided
     if ($image = $request->file('image')) {
   
    // Optionally, delete the old image if it exists (if you want to remove the old image after update)
    if ($blog->image) {
        $oldImagePath = public_path($blog->image);

        // Check if the old image file exists before deleting
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath); // Delete the old image
        } else {
             // Define destination path
            $destinationPath = 'images/'; // Folder where the images will be stored
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension(); // Generate unique filename
            $image->move(public_path($destinationPath), $imageName); // Move the image to the destination folder
            $input['image'] = $destinationPath . $imageName; // Store the image path in the database
        }
    }
     } else {
    // If no new image is provided, retain the current image path
    $input['image'] = $blog->image;
     }

    try {
    // Update the blog post with new data
    $blog->update($input); 

    // Return a success response
    return response()->json(['status' => true, 'message' => 'Blog post updated successfully!'], 200);
    } catch (\Exception $e) {
    // Return an error response in case of an exception
    return response()->json(['status' => false, 'error' => 'Failed to update blog post. ' . $e->getMessage()], 500);
}

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    $blog = Blog::find($id);

    if (!$blog) {
        return response()->json(['status'=>true,'message' => 'Blog not found'], 404);
    }

    $blog->delete();

    return response()->json(['status'=>false,'message' => 'Blog deleted successfully'], 200);
    }

}
