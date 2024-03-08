<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Chat\ChatMessage;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function handle(Request $request)
    {
        // Get the user input
        $message = $request->input('message');

        // Save the chat message to the database
        $chatMessage = new ChatMessage([
            'user_id' => auth()->id(),
            'message' => $message
        ]);
        $chatMessage->save();

        // Process the user input and generate a response
        $response = $this->processMessage($message);

        // Return the response to the user
        return response()->json([
            'message' => $response
        ]);
    }

    private function processMessage($message)
    {
        // Process the user input and generate a response
        $keywords = ['hello', 'hi', 'hey','come home', 'are you fine'];
        $response = 'Sorry, I didn\'t understand what you said.';

        foreach ($keywords as $keyword) {
            if (stripos($message, $keyword) !== false) {
                $response = "hellow there";
                break;
            }
        }

        return $response;
    }
}