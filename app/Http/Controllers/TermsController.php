<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Terms;

class TermsController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'detail' => 'required',
        ]);
    
        // Get all request data
        $input = $request->all();
    
        
        // Try to create a new blog post
        try {
            Terms::create($input); // Create a new Blog record with the input data
    
            // Return a success response
            return response()->json(['status' => "true", 'message' => 'terms post created successfully!'], 200);
        } catch (\Exception $e) {
            // Return an error response in case of an exception
            return response()->json(['status' => "false", 'error' => 'Failed to create terms post. ' . $e->getMessage()], 500);
        }
    }
    public function index()
    {
        $terms=Terms::get();
        if( $terms){
            return response()->json(['status'=>true,'message' => $terms], 200);
        }
        else{
            return response()->json(['status'=>false,'message' =>'Error'], 500);
        }

    }
    public function edit(Request $request,$id)
    {
        $terms = Terms::find($id);
        if ($terms) {
            return response()->json(['status'=>true,'message' => $terms], 200);
        }
        else{
        return response()->json(['status'=>false,'message' => 'Terms Not found'], 500);
        }
    }
    public function update(Request $request, $id)
    {
        // Validate the request data
        $terms = Terms::find($id);
        
        $request->validate([
            'detail' => 'required',
        ]);
     
       
        if (!$terms) {
            return response()->json(['status' => false, 'error' => 'terms post not found'], 404);
        }
    
        // Get all request data
        $input = $request->all();
    
        // Handle image upload if provided


     try {
         // Update the terms post with new data
         $terms->update($input); 
     
         // Return a success response
         return response()->json(['status' => true, 'message' => 'terms post updated successfully!'], 200);
     } catch (\Exception $e) {
         // Return an error response in case of an exception
         return response()->json(['status' => false, 'error' => 'Failed to update terms post. ' . $e->getMessage()], 500);
     }

    }
    public function destroy($id)
    {
    $terms = Terms::find($id);

    if (!$terms) {
        return response()->json(['status'=>true,'message' => 'terms not found'], 404);
    }

    $terms->delete();

    return response()->json(['status'=>false,'message' => 'terms deleted successfully'], 200);
    }
}
