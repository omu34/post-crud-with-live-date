<?php
namespace App\Http\Controllers\Post;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\Post\ShowPostResource;
use App\Http\Requests\Post\DatePostRequest;
use App\Http\Resources\Post\PostResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\Post\Image;
use App\Models\Post\Post;
use Carbon\CarbonPeriod;

class PostController extends Controller
{
    function dateRange(Request $request)
        {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);

            $period = CarbonPeriod::create($startDate, $endDate);

            $dates = array();
            foreach ($period as $date) {
                $dates[] = $date->toDateString();
            }

            return $dates;
        }

    public function index(DatePostRequest $request){
        
        $workspaceId = request()->user()->workspace_id;
        
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $dates = $startDate->toPeriod($endDate, '7 days');

        $dateRange = collect($dates)->map(function($date) {
            return $date->format('Y-m-d');
        });
      
        $posts = Post::with('images','user','workspace')
        ->whereBetween('live_at', $dateRange)
        ->where('workspace_id', $workspaceId)
        ->orderBy('live_at', 'desc')
        ->get();
         
            return ShowPostResource::collection($posts);    
        }

   

    public function show($id)
    {
        $post = Post::findOrFail($id);

        return new PostResource($post);
    }


    public function store(CreatePostRequest $request)
    {
        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->live_at = $request->live_at;
        $post->workspace_id = $request->workspace_id;
        $post->user_id = $request->user_id;      
        $post->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/posts');
                $postImage = new Image();
                $postImage->post_id = $post->user_id;
                $postImage->path = Storage::url($path);
                $postImage->save();
            }
        }

        return response()->json([
            'posts' => $post,            
            'message' => 'Post created successfully'
        ], 201);
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::find($id);
        $post->title = $request->title;
        $post->description = $request->description;
        $post->live_at = $request->live_at;
        $post->workspace_id = $request->workspace_id;
        $post->user_id = $request->user_id;

        $post->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/posts');
                $postImage = new Image();
                $postImage->post_id = $post->id;
                $postImage->path = Storage::url($path);
                $postImage->save();
            }
        }

        return response()->json([
            'posts' => $post,
            'message' => 'Post updated successfully'
        ], 200);
    }

    public function destroy($id, $userId)
    {
        $post = Post::findorFail($id);
        $post->delete();       

        return response()->json([
            'posts' => $post,
            'message' => 'Post deleted successfully'
        ], 200);
    }
}