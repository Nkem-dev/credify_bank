<?php
// app/Http/Controllers/User/PortfolioController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\UserStock;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        $user = auth()->user(); //get currently logged in user
        
        // Get or create investment record
        $investment = Investment::getOrCreateForUser($user->id);
        
        // Update investment summary to get latest values
        $investment->updateSummary();
        
        // Refresh the investment model to get updated values
        $investment->refresh();
        
        // Get all user stocks with stock details
        $portfolio = UserStock::where('user_id', $user->id)
            ->with('stock')
            ->get();
        
        // Add calculated current_value to each portfolio item
        $portfolio = $portfolio->map(function ($userStock) {
            $userStock->current_value = $userStock->quantity * $userStock->stock->current_price;
            return $userStock;
        });
        
        // Sort by current value (highest first)
        $portfolio = $portfolio->sortByDesc('current_value')->values();
        
        // Group by category for organized display
        $portfolioByCategory = $portfolio->groupBy(function ($item) {
            return $item->stock->category;
        });
        
        // return viewew with details
        return view('user.invest.portfolio', compact('investment', 'portfolio', 'portfolioByCategory'));
    }
}