<?php

namespace App\Http\Controllers\Gpt;

use App\Http\Controllers\Controller;
use App\Models\Chat\ChatMessage;
use Illuminate\Http\Request;
use OpenAI;

class GptController extends Controller
{ public function handle(Request $request)
    {
        // Get the user input
        $message = $request->input('message');

        // Save the chat message to the database
        $chatMessage = new ChatMessage([
            'user_id' => auth()->id(),
            'message' => $message
        ]);
        $chatMessage->save();

        // Generate a response using the OpenAI API
        $response = $this->generateResponse($message);

        // Save the chatbot response to the database
        $chatMessage = new ChatMessage([
            'user_id' => null,
            'message' => $response
        ]);
        $chatMessage->save();

        // Return the response to the user
        return response()->json([
            'message' => $response
        ]);
    }

    private function generateResponse($message)
    {
        // Configure the OpenAI API
        OpenAI::setApiKey(config('services.openai.api_key'));

        // Generate a response using the OpenAI API
        $prompt = $message . "\n\nResponse:";
        $completions = OpenAI::completions([
            'model' => 'text-davinci-002',
            'prompt' => $prompt,
            'max_tokens' => 150,
            'temperature' => 0.5,
            'n' => 1,
            'stop' => "\n"
        ]);

        $response = $completions['choices'][0]['text'];

        return $response;
    }



    // public function chat(Request $request)
    // {      
        
        
    //     $message = $request->input('message');
        
    //     // openApi
    //     $response = Http::post('https://api.openai.com/v1/engines/davinci-codex/completions', [
    //         'prompt' => "User: $message\nChatbot:",
    //         'max_tokens' => 50,
    //         'temperature' => 0.5,
    //         'stop' => ['\n']
    //     ])->json()['choices'][0]['text'];

    //     // User response
    //     return response()->json([
    //         'message' => $response
    //     ]);
    // }

    public function getOpenAIResponse(Request $request)
    {
        $api = new Api(env('OPENAI_SECRET_KEY'));

        $prompt = $request->input('prompt');
        $model = $request->input('model');
        $temperature = $request->input('temperature');
        $maxTokens = $request->input('max_tokens');

        $response = $api->completions([
            'engine' => $model,
            'prompt' => $prompt,
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
        ]);

        return $response->choices[0]->text;
    }

    // use GuzzleHttp\Client;

$client = new Client();
$response = $client->request('POST', 'http://example.com/chatbot/openai/response', [
    'form_params' => [
        'prompt' => 'Hello, how are you?',
        'model' => 'davinci',
        'temperature' => 0.5,
        'max_tokens' => 50,
    ]
]);

echo $response->getBody();



// 
public function processRealtimeData(Request $request)
{
    try {
        // Connect to the OpenAI API using your API key
        $api = new Api(env('OPENAI_SECRET_KEY'));

        // Get the real-time data from the request
        $data = $request->input('data');

        // Process the real-time data using OpenAI
        $response = $api->completions([
            'prompt' => $data,
            'model' => 'davinci',
            'temperature' => 0.5,
            'max_tokens' => 50,
        ]);

        // Return the generated text as a response
        return $response->choices[0]->text;
    } catch (\Exception $e) {
        // Handle any errors that occur while processing the real-time data
        return response()->json([
            'error' => $e->getMessage(),
        ], 500);
    }
}

}