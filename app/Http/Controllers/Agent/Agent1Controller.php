<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent\Agent;
use App\Models\Agent\Message;
use Illuminate\Http\Request;

class Agent1Controller extends Controller
{
    public function index()
    {
        $agents = Agent::all();

        $metrics = [];

        foreach ($agents as $agent) {
            $agentMetrics = new \stdClass;

            
            $conversations = Message::where('agent_id', $agent->id)
                                     ->selectRaw('COUNT(DISTINCT conversation_id) as count')
                                     ->first();
            $agentMetrics->conversations = $conversations->count;
            
            $ongoingMessages = Message::where('agent_id', $agent->id)
                                      ->whereNull('resolved_at')
                                      ->count();
            $agentMetrics->ongoing_messages = $ongoingMessages;
            
            $firstResponses = Message::where('agent_id', $agent->id)
                                      ->whereNotNull('first_response_at')
                                      ->whereNotNull('resolved_at')
                                      ->get();

            $totalResponseTime = 0;
            foreach ($firstResponses as $response) {
                $responseTime = $response->first_response_at->diffInSeconds($response->resolved_at);
                $totalResponseTime += $responseTime;
            }

            if (count($firstResponses) > 0) {
                $averageResponseTime = $totalResponseTime / count($firstResponses);
            } else {
                $averageResponseTime = 0;
            }

            $agentMetrics->average_response_time = $averageResponseTime;
            
            $firstResponse = Message::where('agent_id', $agent->id)
                                    ->whereNotNull('first_response_at')
                                    ->min('first_response_at');
            if ($firstResponse) {
                $agentMetrics->first_response_time = $firstResponse->diffInSeconds($agent->created_at);
            } else {
                $agentMetrics->first_response_time = null;
            }

            $resolvedMessages = Message::where('agent_id', $agent->id)
                                        ->whereNotNull('resolved_at')
                                        ->get();

            $totalResolutionTime = 0;
            foreach ($resolvedMessages as $message) {
                $resolutionTime = $message->created_at->diffInSeconds($message->resolved_at);
                $totalResolutionTime += $resolutionTime;
            }

            if (count($resolvedMessages) > 0) {
                $averageResolutionTime = $totalResolutionTime / count($resolvedMessages);
            } else {
                $averageResolutionTime = 0;
            }

            $agentMetrics->average_resolution_time = $averageResolutionTime;
            
            $resolutionCount = Message::where('agent_id', $agent->id)
                                       ->whereNotNull('resolved_at')
                                       ->count();
            $agentMetrics->resolution_count = $resolutionCount;

            $metrics[$agent->id] = $agentMetrics;
        }

        return response()->json(['data' => $metrics]);
    }
}