<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Like;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with('user', 'likes')->get();

        if (request()->ajax())
        {
            $html = view('quote.list', compact('quotes'))->render();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }
        else
        {
            return view('quote.index', compact('quotes'));
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quote' => 'required|string|max:255',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $quote = new Quote;
        $quote->quote = $request->quote;
        $quote->user_id = Auth::id();
        $quote->save();

        return response()->json([
            'message' => 'Quote added successfully!',
            'success' => true,
        ]);
    }

    public function likeDislike(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'action' => 'required|in:like,dislike',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $quote = Quote::find($request->id);

        if(!$quote){
            return response()->json(['success' => false,"message" => "The quote is not found"]);
        }

        $userId = Auth::id();
        

        if ($quote->user_id == $userId)
        {
            return response()->json(['message' => 'You cannot like or dislike your own quote!','success' => false]);
        }

        $existingLike =Like::where('user_id',$userId)->where('like',1)->where('quote_id',$quote->id)->first();
        $existingDisLike =Like::where('user_id',$userId)->where('like',0)->where('quote_id',$quote->id)->first();

        if($request->action =='like'){
            if($existingLike){
                if($existingLike->user_id == $userId){
                    $existingLike->delete();
                    return response()->json(['message' => 'Like remove successfully','success' => true]);
                }else {
                    return response()->json(['message' => 'You can only remove your own like!', 'success' => false]);
                }
                
            }else{
                $this->newLikeOrDislike($userId,$quote->id,1);
                return response()->json(['message' => 'Like added successfully','success' => true]);
            }
        }else{
            if($existingDisLike){

                if($existingDisLike->user_id == $userId){
                    $existingDisLike->delete();
                    return response()->json(['message' => 'DisLike remove successfully','success' => true]);
                }else {
                    return response()->json(['message' => 'You can only remove your own dislike!', 'success' => false]);
                }
            }else{
                $this->newLikeOrDislike($userId,$quote->id,0);
                return response()->json(['message' => 'DisLike added successfully','success' => true]);
            }
        }

    }

    private function newLikeOrDislike($userId,$quoteId, $isLike){

        $checkUserLikeOrDislike=Like::where('user_id',$userId)->where('quote_id',$quoteId)->first();
        if($checkUserLikeOrDislike){
            $like=$checkUserLikeOrDislike;
        }else{
            $like=new Like();
        }
        $like->user_id=$userId;
        $like->quote_id=$quoteId;
        $like->like=$isLike;
        $like->save();
    }
}
