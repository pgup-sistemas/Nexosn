<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function show(string $slug)
    {
        $card = Card::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('card.show', compact('card'));
    }
}
