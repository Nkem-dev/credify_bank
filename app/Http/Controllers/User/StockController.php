<?php
// app/Http/Controllers/User/StockController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\UserStock;
use App\Models\StockTransaction;
use App\Models\Investment;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Hash;

class StockController extends Controller
{
    // show stock index view
    public function index(Request $request)
    {
        // get filter inputs from the request: shows in url as ?category=banking&search=mtn. gets the category and what the user searched for
        $category = $request->get('category');
        $search = $request->get('search');
        
        // get active stocks when the yser search and show 12 per page
        $stocks = Stock::where('is_active', true)
            ->when($category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('symbol', 'like', "%{$search}%");
                });
            })
            ->orderBy('name') 
            ->paginate(12);//show 12 per page
        
            // available categories for dropdown
        $categories = [
            'banking' => 'Banking',
            'oil_gas' => 'Oil & Gas',
            'technology' => 'Technology',
            'manufacturing' => 'Manufacturing',
            'telecommunications' => 'Telecommunications',
            'consumer_goods' => 'Consumer Goods',
        ];
        
        // return view with stock details
        return view('user.invest.stocks.index', compact('stocks', 'categories', 'category', 'search'));
    }
    
    // view details of the stock
    public function show(Stock $stock) //laravel model binding
    {
        // Check if user owns this stock
        $userStock = auth()->user()->userStocks()
            ->where('stock_id', $stock->id)
            ->first();
        
        // Check if stock is in user's watchlist
        $inWatchlist = auth()->user()->watchlist()
            ->where('stock_id', $stock->id)
            ->exists();
        
        // Get recent transactions for this stock
        $recentTransactions = auth()->user()->stockTransactions()
            ->where('stock_id', $stock->id)
            ->latest()
            ->take(10) //show 10 recent transactions
            ->get();
        
            // return view with details
        return view('user.invest.stocks.show', compact('stock', 'userStock', 'inWatchlist', 'recentTransactions'));
    }

    // buy stocks
   public function buy(Request $request, Stock $stock)
{  //validate inputs
    $request->validate([
        'quantity' => 'required|integer|min:1',
        'transaction_pin' => 'required|digits:4',
    ]);

    $user = auth()->user(); //currently logged in user
    $quantity = $request->quantity; //get the quantity of shares the user wants to buy
    $pricePerShare = $stock->current_price; //get the price of one share(stock)
    $totalAmount = $quantity * $pricePerShare; //calculate the total cost
    
    // Calculate transaction fee (0.5%)
    $transactionFee = $totalAmount * 0.005; //0.5% of the total purchase
    $netAmount = $totalAmount + $transactionFee; //amount the user will be charged

    // Verify user transaction PIN
            if (!Hash::check($request->transaction_pin, $user->transaction_pin)) {
                return redirect()->back()->with('error', 'Invalid transaction PIN.');
            }
        
       

    // Check if user has sufficient balance
    if ($user->balance < $netAmount) {
        return redirect()->back()->with('error', 'Insufficient balance. You need ₦' . number_format($netAmount, 2) . ' but have ₦' . number_format($user->balance, 2));
    }

    try {
        DB::beginTransaction(); //begin task

        // Deduct from user balance
        $user->balance -= $netAmount;
        $user->save(); //save to databse

        // Check if user already owns this stock
        $userStock = UserStock::where('user_id', $user->id)
            ->where('stock_id', $stock->id)
            ->first();

            // if the user already owns a particular stock , update the existing stock record
        if ($userStock) {
            // Update existing holding
            $totalShares = $userStock->quantity + $quantity;
            $totalInvested = $userStock->total_invested + $totalAmount;
            $averagePrice = $totalInvested / $totalShares;

            $userStock->update([
                'quantity' => $totalShares,
                'total_invested' => $totalInvested,
                'average_buy_price' => $averagePrice,
            ]);
        } else { //else , if the user does not own an existing stock
            // Create new stock record
            UserStock::create([
                'user_id' => $user->id,
                'stock_id' => $stock->id,
                'quantity' => $quantity,
                'average_buy_price' => $pricePerShare,
                'total_invested' => $totalAmount,
            ]);
        }

        // Record transaction
        StockTransaction::create([
            'user_id' => $user->id,
            'stock_id' => $stock->id,
            'type' => 'buy',
            'quantity' => $quantity,
            'price_per_share' => $pricePerShare,
            'total_amount' => $totalAmount,
            'transaction_fee' => $transactionFee,
            'net_amount' => $netAmount,
            'status' => 'completed',
            'reference' => 'STK-BUY-' . strtoupper(Str::random(10)),
            'description' => "Purchased {$quantity} shares of {$stock->name}",
        ]);

        

        // Update investment summary
        $investment = Investment::getOrCreateForUser($user->id);
        $investment->updateSummary();

        DB::commit(); //commit to database

        // return with success message
        return redirect()->back()->with('success', "Successfully purchased {$quantity} shares of {$stock->name} for ₦" . number_format($netAmount, 2));

    } catch (Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to purchase stock: ' . $e->getMessage());
    }
}

// sell stock
    public function sell(Request $request, Stock $stock)
    { //validate input
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'transaction_pin' => 'required|digits:4',
        ]);

        $user = auth()->user(); //get authenticated user
        $quantity = $request->quantity; //get the quantity the user requests

        // Verify user transaction PIN
            if (!Hash::check($request->transaction_pin, $user->transaction_pin)) {
                return redirect()->back()->with('error', 'Invalid transaction PIN.');
            }

       
        // confirm if user owns stock
        $userStock = UserStock::where('user_id', $user->id)
            ->where('stock_id', $stock->id)
            ->first();

            // if the user does not own the stock
        if (!$userStock) {
            return redirect()->back()->with('error', 'You do not own this stock.');
        }

        // if the stock quantity is less than the quantity requested to check if user has enough shares
        if ($userStock->quantity < $quantity) {
            return redirect()->back()->with('error', "Insufficient shares. You only have {$userStock->quantity} shares.");
        }

        // calculate sale value (user receives total amount but 0.5% fee is deducted)
        $pricePerShare = $stock->current_price; //stock current price
        $totalAmount = $quantity * $pricePerShare; //quantity * stcok price
        
        // Calculate transaction fee (0.5%)
        $transactionFee = $totalAmount * 0.005; //multiply total amount with the fee
        $netAmount = $totalAmount - $transactionFee; //deduct fee from the amount

        try {
            DB::beginTransaction(); //begin task

            // Credit user balance
            $user->balance += $netAmount;
            $user->save(); //save to database

            // if the user sells all shares , delete the stock record
            if ($userStock->quantity == $quantity) { //if the stock shares quantity is equals to the quantity the user requested to sell
                // Selling all shares - delete the record
                $userStock->delete();
            } else {
                // Partial sale - update quantity and total invested
                $remainingQuantity = $userStock->quantity - $quantity;
                $soldInvestment = ($quantity / $userStock->quantity) * $userStock->total_invested;
                $remainingInvestment = $userStock->total_invested - $soldInvestment;

                $userStock->update([
                    'quantity' => $remainingQuantity,
                    'total_invested' => $remainingInvestment,
                ]);
            }

            // Record transaction
            StockTransaction::create([
                'user_id' => $user->id,
                'stock_id' => $stock->id,
                'type' => 'sell',
                'quantity' => $quantity,
                'price_per_share' => $pricePerShare,
                'total_amount' => $totalAmount,
                'transaction_fee' => $transactionFee,
                'net_amount' => $netAmount,
                'status' => 'completed',
                'reference' => 'STK-SELL-' . strtoupper(Str::random(10)),
                'description' => "Sold {$quantity} shares of {$stock->name}",
            ]);

            // Update investment summary
            $investment = Investment::getOrCreateForUser($user->id);
            $investment->updateSummary();

            DB::commit(); //commit to database

            // return with success message
            return redirect()->back()->with('success', "Successfully sold {$quantity} shares of {$stock->name} for ₦" . number_format($netAmount, 2));

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to sell stock: ' . $e->getMessage());
        }
    }
}