<?php

namespace App\Http\Controllers;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Http\Request;

class OpenAIController extends Controller
{
    public function show(Request $request)
    {
        $prompt = $request->input('prompt');

        $response = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
        ]);

        dd($response);

        return response()->json($response);
    }
}
