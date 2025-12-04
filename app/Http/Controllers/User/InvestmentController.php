<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\UserStock;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    
      public function index()
    {
        $user = auth()->user(); //get currently logged in user
        
        // Get or create investment record: ensures user has one investment summary record
        $investment = Investment::getOrCreateForUser($user->id);
        
        // Get the stocks that the user owns and load with related data
        $portfolio = UserStock::where('user_id', $user->id)
            ->with('stock')
            ->get()
            ->map(function ($userStock) {
                // Calculate current value of each stock = quantity × current price
                $userStock->current_value = $userStock->quantity * $userStock->stock->current_price;
                return $userStock;
            })
            ->sortByDesc('current_value') //sort by highest value
            ->take(5); //load user's top 5 woned stocks
        
        // Get trending stocks 
        $trendingStocks = Stock::where('is_active', true) //only active stocks
            ->orderBy('price_change_percentage', 'desc') //sort by highest gainers
            ->take(6) //take top 6 trending stocks
            ->get();
        
        // Get recent stock transactions
        $recentTransactions = StockTransaction::where('user_id', $user->id)
            ->with('stock')
            ->latest() 
            ->take(5) //take 5 recent transactions
            ->get();
        
        // count how many stocks that the user has saved to watchlist
        $watchlistCount = $user->watchlist()->count();
        
        // return view with details
        return view('user.invest.index', compact(
            'investment',
            'portfolio',
            'trendingStocks',
            'recentTransactions',
            'watchlistCount'
        ));
    }

    //  public function transactions()
    // {
    //     $transactions = StockTransaction::where('user_id', auth()->id())
    //         ->with('stock')
    //         ->latest()
    //         ->paginate(20);
        
    //     return view('user.invest.transactions', compact('transactions'));
    // }

    // Add this method to your InvestmentController

public function transactions()
{
    $user = auth()->user();
    
    // Get all transactions with stock details, ordered by most recent
    $transactions = StockTransaction::where('user_id', $user->id)
        ->with('stock')
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    
    // Calculate summary statistics
    $totalBuys = StockTransaction::where('user_id', $user->id)
        ->where('type', 'buy')
        ->sum('total_amount');
    
    $totalSells = StockTransaction::where('user_id', $user->id)
        ->where('type', 'sell')
        ->sum('total_amount');
    
    $totalTransactions = StockTransaction::where('user_id', $user->id)->count();
    
    $buyCount = StockTransaction::where('user_id', $user->id)
        ->where('type', 'buy')
        ->count();
    
    $sellCount = StockTransaction::where('user_id', $user->id)
        ->where('type', 'sell')
        ->count();
    
    return view('user.invest.transactions', compact(
        'transactions',
        'totalBuys',
        'totalSells',
        'totalTransactions',
        'buyCount',
        'sellCount'
    ));
}
}
