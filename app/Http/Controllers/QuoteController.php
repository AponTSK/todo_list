<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with('user')->get();


        if (request()->ajax())
        {
            $html = view('quotelist', compact('quotes'))->render();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }
        else
        {
            return view('quote', compact('quotes'));
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quote' => 'required|string|max:255',
        ], [
            'quote.required' => 'The task field cannot be empty. Please provide a task.',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()->all(),
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
        $quote = Quote::find($request->id);
        $response = auth()->user()->toggleLikeDislike($quote->id, $request->like);

        return response()->json(['success' => $response]);
    }
}
