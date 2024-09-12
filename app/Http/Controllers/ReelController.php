<?php

namespace App\Http\Controllers;

use App\Models\Reel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ReelProgress;

class ReelController extends Controller
{
    
    public function uploadReel(Request $request)
    {
        // Custom validation rules and messages
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'video' => 'required|mimes:mp4,mov,avi,wmv|max:20000', // Max 20 MB
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional thumbnail
        ], [
            'title.required' => 'The title field is required.',
            'video.required' => 'The video file is required.',
            'video.mimes' => 'The video must be a file of type: mp4, mov, avi, wmv.',
            'video.max' => 'The video size must not exceed 20 MB.',
            'thumbnail.image' => 'The thumbnail must be an image.',
            'thumbnail.mimes' => 'The thumbnail must be a file of type: jpeg, png, jpg, gif.',
            'thumbnail.max' => 'The thumbnail size must not exceed 2 MB.',
        ]);
    
        // If validation fails, return the error response
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
    
        // Get the authenticated user
        $user = auth()->user();
    
        // Handle video upload
        $videoPath = $request->hasFile('video') ? $request->file('video')->store('reels/videos', 'public') : null;
    
        // Handle thumbnail upload (optional)
        $thumbnailPath = $request->hasFile('thumbnail') ? $request->file('thumbnail')->store('reels/thumbnails', 'public') : null;
    
        // Store the reel in the database
        $reel = Reel::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'video_path' => $videoPath,
            'thumbnail_path' => $thumbnailPath,
        ]);
    
        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Reel uploaded successfully',
            'data' => [
                'reel' => $reel
            ]
        ], 201);
    }
    
    

    // Retrieve all reels
    public function getReels()
    {
        try {
            // Fetch reels with optional user information
            $reels = Reel::with('user')->get();
    
            // Check if reels collection is empty
            if ($reels->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No reels found',
                    'data' => [
                        'reels' => []
                    ]
                ], 200);
            }
    
            // Return a well-structured JSON response with reels
            return response()->json([
                'status' => 'success',
                'message' => 'Reels retrieved successfully',
                'data' => [
                    'reels' => $reels
                ]
            ], 200);
    
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve reels',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    

    // Retrieve a specific reel by ID
    public function getReel($id)
    {
        try {
            // Find the reel by ID
            $reel = Reel::find($id);
    
            // Check if the reel was not found
            if (!$reel) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Reel not found',
                    'data' => null
                ], 404);
            }
    
            // Return a well-structured JSON response with the reel
            return response()->json([
                'status' => 'success',
                'message' => 'Reel retrieved successfully',
                'data' => [
                    'reel' => $reel
                ]
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve reel',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    // Save last played second    
    public function saveLastSecond(Request $request)
    {
        // Custom validation rules and messages
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'reel_id' => 'required|integer',
            'last_second' => 'required|integer',
        ], [
            'user_id.required' => 'The user ID field is required.',
            'user_id.integer' => 'The user ID must be an integer.',
            'reel_id.required' => 'The reel ID field is required.',
            'reel_id.integer' => 'The reel ID must be an integer.',
            'last_second.required' => 'The last second field is required.',
            'last_second.integer' => 'The last second must be an integer.',
        ]);
    
        // If validation fails, return the error response
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            // Check if the record exists, if not, create it
            $progress = ReelProgress::updateOrCreate(
                ['user_id' => $request->user_id, 'reel_id' => $request->reel_id],
                ['last_second' => $request->last_second]
            );
    
            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Last played second saved successfully',
                'data' => [
                    'user_id' => $progress->user_id,
                    'reel_id' => $progress->reel_id,
                    'last_second' => $progress->last_second
                ]
            ], 200);
    
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save last played second',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    

    // Retrieve last played second for a reel by user
    public function getLastSecond(Request $request, $reel, $user)
    {
        // Custom validation rules and messages
        $validator = Validator::make([
            'reel_id' => $reel,
            'user_id' => $user,
        ], [
            'reel_id' => 'required|integer',
            'user_id' => 'required|integer',
        ], [
            'reel_id.required' => 'The reel ID is required.',
            'reel_id.integer' => 'The reel ID must be an integer.',
            'user_id.required' => 'The user ID is required.',
            'user_id.integer' => 'The user ID must be an integer.',
        ]);
    
        // If validation fails, return the error response
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
    
        // Retrieve the last played second from the database
        $progress = ReelProgress::where('user_id', $user)
                                ->where('reel_id', $reel)
                                ->first();
    
        // If progress exists, return success with last second played
        if ($progress) {
            return response()->json([
                'status' => 'success',
                'message' => 'Last second retrieved successfully',
                'data' => [
                    'user_id' => $user,
                    'reel_id' => $reel,
                    'last_second' => $progress->last_second
                ]
            ], 200);
        }
    
        // Return success but with default last second (0) if no progress found
        return response()->json([
            'status' => 'success',
            'message' => 'No progress found, returning default last second',
            'data' => [
                'user_id' => $user,
                'reel_id' => $reel,
                'last_second' => 0
            ]
        ], 200);
    }
    
    
}
