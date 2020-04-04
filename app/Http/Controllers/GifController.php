<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\History;
use App\Favorite;
use App\ShortURL;

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

        // conver whitespaces to %20
        $url = str_replace (' ', '%20', $url);

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

        // save encoded string for every GIF id and return it
        foreach($responseData->data as $index => $gif) {
            $short_url = ShortURL::where('gif_id', '=', $gif->id)->first();
            if(!$short_url) {
                $short_url = new ShortURL;
                $short_url->gif_id = $gif->id;
                $short_url->save();
            }

            $responseData->data[$index]->short_url = $short_url->encode();
        }

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
     * Search GIPHY Public API by GIF id
     *
     * @param   Request $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function searchById(Request $request)
    {
        $url = "https://api.giphy.com/v1/gifs/" . $request->input('gif_id') . "?api_key=" . env('GIPHY_API_KEY');

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

        // get short url for this gif
        $short_url = ShortURL::where('gif_id', '=', $responseData->data->id)->first();
        if($short_url) {
            $responseData->data->short_url = $short_url->encode();
        }


        return response()->json($responseData);
    }

    /**
     * Decode Shorten URL and return GIF id
     *
     * @param   $url
     *
     * @return  Illuminate\Http\RedirectResponse
     */
    public function decodeURL($url) {
        $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $limit = strlen($url);
        $decoded = strpos($base, $url[0]);
        for($i = 1; $i < $limit; $i++) {
            $decoded = 62 * $decoded + strpos($base, $url[$i]);
        }

        $short_url = ShortURL::find($decoded);

        return redirect('gif/' . $short_url->gif_id);
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

    /**
     * Set Favorite GIF for Logged In User
     *
     * @param   Request $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function favorite(Request $request)
    {
        if (Auth::check()) {
            $favorite_exists = Favorite::where([
                ['gif_id', '=', $request->input('gif_id')],
                ['user_id', '=', Auth::id()]
            ])->first();

            if (!$favorite_exists) {
                $favorite = new Favorite;
                $favorite->user_id = Auth::id();
                $favorite->gif_id = $request->input('gif_id');
                $favorite->gif_title = $request->input('gif_title');
                $favorite->thumb_url = $request->input('thumb_url');
                $favorite->thumb_width = $request->input('thumb_width');
                $favorite->thumb_height = $request->input('thumb_height');
                $favorite->mp4_url = $request->input('mp4_url');
                $favorite->save();

                return response()->json($favorite);
            } else {
                return response()->json(['message' => 'Gif already favorited'], 400);
            }
        }
    }

    /**
     * Remove Favorite GIF for Logged In User
     *
     * @param   Request $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function unfavorite(Request $request)
    {
        if (Auth::check()) {
            $favorite_exists = Favorite::where([
                ['gif_id', '=', $request->input('gif_id')],
                ['user_id', '=', Auth::id()]
            ])->first();

            if ($favorite_exists) {
                $favorite_exists->delete();
                return response()->json($favorite_exists);
            } else {
                return response()->json(['message' => 'Gif not favorited'], 400);
            }
        }
    }
}
