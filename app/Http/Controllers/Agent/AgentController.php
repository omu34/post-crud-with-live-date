<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent\Agent;
use App\Models\Agent\Message;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function conversationsByAgent(Request $request)
    {
        $agents = Agent::all();
        $response = [];
        foreach ($agents as $agent) {
            $conversations = Message::where('agent_id', $agent->id)
                ->whereNotNull('ended_at')
                ->get();
            $totalDuration = 0;
            foreach ($conversations as $conversation) {
                $duration = $conversation->ended_at->diffInSeconds($conversation->started_at);
                $totalDuration += $duration;
            }
            $response[] = [
                'agent_name' => $agent->name,
                'total_duration' => $totalDuration,
            ];
        }
        return response()->json([
            'data' => $response,            
            'message' => 'Conversation(s) retrivied successfully'
        ],201); 
    }
    
    public function ongoingMessages(Request $request)
    {
        $messages = Message::whereNull('ended_at')->get();
        $response = [];
        foreach ($messages as $message) {
            $response[] = [
                'agent_name' => $message->agent->name,
                'customer_name' => $message->customer_name,
                'started_at' => $message->started_at,
                'last_message' => $message->last_message,
            ];
        }
        return response()->json([
            'data' => $response,            
            'message' => 'onGoing message(s) retrivied successfully'
        ],201); 
    }
    
    public function responseTime(Request $request)
    {
        $messages = Message::whereNotNull('ended_at')->get();
        $response = [];
        foreach ($messages as $message) {
            $responseTime = $message->started_at->diffInSeconds($message->first_response_at);
            $response[] = [
                'agent_name' => $message->agent->name,
                'customer_name' => $message->customer_name,
                'response_time' => $responseTime,
            ];
        }
        return response()->json([
            'data' => $response,            
            'message' => 'ResponseTime(s) retrivied successfully'
        ],201); 
    }
    
    public function responseAndResolutionTime(Request $request)
    {
        $messages = Message::whereNotNull('ended_at')->get();
        $response = [];
        foreach ($messages as $message) {
            $firstResponseTime = $message->started_at->diffInSeconds($message->first_response_at);
            $resolutionTime = $message->started_at->diffInSeconds($message->ended_at);
            $response[] = [
                'agent_name' => $message->agent->name,
                'customer_name' => $message->customer_name,
                'first_response_time' => $firstResponseTime,
                'resolution_time' => $resolutionTime,
            ];
        }
        return response()->json([
            'data' => $response,            
            'message' => 'Response and ResolutionTime(s) retrivied successfully'
        ],201); 
    }
    

    public function resolutionCount(Request $request)
{
    $agents = Agent::all();
    $response = [];
    foreach ($agents as $agent) {
        $resolvedCount = Message::where('agent_id', $agent->id)
            ->whereNotNull('ended_at')
            ->where('resolved', true)
            ->count();
        $response[] = [
            'agent_name' => $agent->name,
            'resolved_count' => $resolvedCount,
        ];
    }
    return response()->json([
        'data' => $response,            
        'message' => 'Resolution count(s) retrivied successfully'
    ],201); 
}
}