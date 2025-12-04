<?php
// app/Http/Controllers/User/WatchlistController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Watchlist;
use Exception;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    // show inddex view of the watchlist
    public function index()
    {
        // get the authenticated user's watchlist items with related stock
        $watchlist = Watchlist::where('user_id', auth()->id())
            ->with('stock')
            ->latest()
            ->get();
        // return view with watchlist details
        return view('user.invest.watchlist', compact('watchlist'));
    }
    // add stock item to user's watchlist
    public function add(Stock $stock)
    {
        try {
            Watchlist::firstOrCreate([
                'user_id' => auth()->id(), //check if user exists
                'stock_id' => $stock->id, //if the stock exists
            ]);
            
            // return with success message
            return redirect()->back()->with('success', "{$stock->name} added to watchlist!");
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to add to watchlist.');
        }
    }
    
    // remove stock from user's watchlist
    public function remove(Stock $stock)
    {
        try {
            // find the user ande delete the watchlist record
            Watchlist::where('user_id', auth()->id())
                ->where('stock_id', $stock->id)
                ->delete();
            
                // return with success message
            return redirect()->back()->with('success', "{$stock->name} removed from watchlist!");
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove from watchlist.');
        }
    }
}