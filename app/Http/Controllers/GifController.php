<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\History;

class GifController extends Controller
{
    /**
     * Search GIPHY Public API
     *
     * @param   Request $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $url = "https://api.giphy.com/v1/gifs/search?" .
            "api_key=" . env('GIPHY_API_KEY') .
            "&q=" . $request->input('query_string') .
            "&offset=" . $request->input('offset') .
            "&limit=24&rating=G&lang=en";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        $responseData = json_decode($responseData);

        // save to history if user is logged in and its direct search
        if(Auth::check() && $request->input('direct_search')) {
            $history = new History;
            $history->user_id = Auth::id();
            $history->query_string = $request->input('query_string');
            $history->save();
        }

        return response()->json($responseData);
    }

    /**
     * Get Search History for Logged In User
     *
     * @param   Request $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function history(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            return response()->json($user->history);
        }
    }
}
