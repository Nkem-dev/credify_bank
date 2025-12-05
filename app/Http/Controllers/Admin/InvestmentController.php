<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\User;
use App\Models\UserStock;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvestmentController extends Controller
{
      public function index()
    {
        // Get overall investment statistics
        $totalInvestments = Investment::sum('total_invested');
        $totalCurrentValue = Investment::sum('current_value');
        $totalProfitLoss = Investment::sum('total_profit_loss');
        $totalDividends = Investment::sum('total_dividends');
        
        // Count active investors (users with investments)
        $activeInvestors = Investment::whereNotNull('user_id')->count();
        
        // Get stock statistics
        $totalStocks = Stock::where('is_active', true)->count();
        $totalStockTransactions = StockTransaction::count();
        $totalTransactionVolume = StockTransaction::where('status', 'completed')->sum('total_amount');
        
        // Recent investments summary
        $recentInvestments = Investment::with('user')
            ->latest('updated_at')
            ->take(10)
            ->get();
        
        // Top performing stocks
        $topStocks = Stock::where('is_active', true)
            ->orderByDesc('price_change_percentage')
            ->take(5)
            ->get();
        
        // Top investors by total invested
        $topInvestors = Investment::with('user')
            ->orderByDesc('total_invested')
            ->take(5)
            ->get();
        
        // Recent stock transactions
        $recentTransactions = StockTransaction::with(['user', 'stock'])
            ->latest()
            ->take(10)
            ->get();
        
        // Calculate growth metrics
        $profitableInvestors = Investment::where('total_profit_loss', '>', 0)->count();
        $losingInvestors = Investment::where('total_profit_loss', '<', 0)->count();
        
        $profitablePercentage = $activeInvestors > 0 
            ? ($profitableInvestors / $activeInvestors) * 100 
            : 0;
        
        // Transaction type breakdown
        $buyTransactions = StockTransaction::where('type', 'buy')
            ->where('status', 'completed')
            ->count();
        $sellTransactions = StockTransaction::where('type', 'sell')
            ->where('status', 'completed')
            ->count();
        
        // Stock categories
        $stocksByCategory = Stock::select('category', DB::raw('count(*) as total'))
            ->where('is_active', true)
            ->groupBy('category')
            ->get();
        
        return view('admin.investments.index', compact(
            'totalInvestments',
            'totalCurrentValue',
            'totalProfitLoss',
            'totalDividends',
            'activeInvestors',
            'totalStocks',
            'totalStockTransactions',
            'totalTransactionVolume',
            'recentInvestments',
            'topStocks',
            'topInvestors',
            'recentTransactions',
            'profitableInvestors',
            'losingInvestors',
            'profitablePercentage',
            'buyTransactions',
            'sellTransactions',
            'stocksByCategory'
        ));
    }

    // View all user investments
    // public function allInvestments()
    // {
    //     $investments = Investment::with('user')
    //         ->paginate(20);

            
        
    //     return view('admin.investments.all-investments', compact('investments'));
    // }

    public function allInvestments()
{
    $investments = Investment::with(['user.userStocks'])
        ->paginate(20);
    
    return view('admin.investments.all-investments', compact('investments'));
}
    
    // View specific user investment details
    public function showUserInvestment($userId)
    {
        $user = User::findOrFail($userId);
        $investment = Investment::where('user_id', $userId)->firstOrFail();
        
        // Get user's stocks portfolio
        $userStocks = UserStock::where('user_id', $userId)
            ->with('stock')
            ->get();
        
        // Get user's transactions
        $transactions = StockTransaction::where('user_id', $userId)
            ->with('stock')
            ->latest()
            ->paginate(15);
        
        // Get user's watchlist
        $watchlist = Watchlist::where('user_id', $userId)
            ->with('stock')
            ->get();
        
        return view('admin.investments.user-details', compact(
            'user',
            'investment',
            'userStocks',
            'transactions',
            'watchlist'
        ));
    }
    
    // View all stocks
    public function allStocks()
    {
        $stocks = Stock::withCount('userStocks')
            ->paginate(20);
        
        return view('admin.investments.all-stocks', compact('stocks'));
    }
    
    
    // View specific stock details
    public function showStock($stockId)
    {
        $stock = Stock::findOrFail($stockId);
        
        // Get investors holding this stock
        $holders = UserStock::where('stock_id', $stockId)
            ->with('user')
            ->get();
        
        // Get recent transactions for this stock
        $transactions = StockTransaction::where('stock_id', $stockId)
            ->with('user')
            ->latest()
            ->paginate(15);
        
        // Get users watching this stock
        $watchers = Watchlist::where('stock_id', $stockId)
            ->with('user')
            ->get();
        
        return view('admin.investments.stock-details', compact(
            'stock',
            'holders',
            'transactions',
            'watchers'
        ));
    }
    
    // View all stock transactions
    public function allTransactions()
    {
        $transactions = StockTransaction::with(['user', 'stock'])
            ->latest()
            ->paginate(20);
        
        return view('admin.investments.all-transactions', compact('transactions'));
    }
    
    // View all user stocks (portfolios)
    public function allUserStocks()
    {
        $userStocks = UserStock::with(['user', 'stock'])
            ->paginate(20);
        
        return view('admin.investments.all-user-stocks', compact('userStocks'));
    }
    
    // View all watchlists
    public function allWatchlists()
    {
        $watchlists = Watchlist::with(['user', 'stock'])
            ->latest()
            ->paginate(20);
        
        return view('admin.investments.all-watchlists', compact('watchlists'));
    }
    
    // Create/Edit Stock
    public function createStock()
    {
        return view('admin.investments.create-stock');
    }
    
    public function storeStock(Request $request)
    {
        $validated = $request->validate([
            'symbol' => 'required|string|unique:stocks,symbol',
            'name' => 'required|string',
            'logo' => 'nullable|string',
            'category' => 'required|string',
            'current_price' => 'required|numeric|min:0',
            'opening_price' => 'required|numeric|min:0',
            'day_high' => 'nullable|numeric|min:0',
            'day_low' => 'nullable|numeric|min:0',
            'week_high' => 'nullable|numeric|min:0',
            'week_low' => 'nullable|numeric|min:0',
            'market_cap' => 'nullable|numeric|min:0',
            'volume' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        // Calculate price change
        $priceChange = $validated['current_price'] - $validated['opening_price'];
        $priceChangePercentage = $validated['opening_price'] > 0 
            ? (($priceChange / $validated['opening_price']) * 100) 
            : 0;
        
        $validated['price_change'] = $priceChange;
        $validated['price_change_percentage'] = $priceChangePercentage;
        
        Stock::create($validated);
        
        return redirect()->route('admin.investments.stocks.all')
            ->with('success', 'Stock created successfully!');
    }
    
    public function editStock($stockId)
    {
        $stock = Stock::findOrFail($stockId);
        return view('admin.investments.edit-stock', compact('stock'));
    }
    
    public function updateStock(Request $request, $stockId)
    {
        $stock = Stock::findOrFail($stockId);
        
        $validated = $request->validate([
            'symbol' => 'required|string|unique:stocks,symbol,' . $stockId,
            'name' => 'required|string',
            'logo' => 'nullable|string',
            'category' => 'required|string',
            'current_price' => 'required|numeric|min:0',
            'opening_price' => 'required|numeric|min:0',
            'day_high' => 'nullable|numeric|min:0',
            'day_low' => 'nullable|numeric|min:0',
            'week_high' => 'nullable|numeric|min:0',
            'week_low' => 'nullable|numeric|min:0',
            'market_cap' => 'nullable|numeric|min:0',
            'volume' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        // Calculate price change
        $priceChange = $validated['current_price'] - $validated['opening_price'];
        $priceChangePercentage = $validated['opening_price'] > 0 
            ? (($priceChange / $validated['opening_price']) * 100) 
            : 0;
        
        $validated['price_change'] = $priceChange;
        $validated['price_change_percentage'] = $priceChangePercentage;
        
        $stock->update($validated);
        
        return redirect()->route('admin.investments.stocks.all')
            ->with('success', 'Stock updated successfully!');
    }
    
    public function toggleStockStatus($stockId)
    {
        $stock = Stock::findOrFail($stockId);
        $stock->update(['is_active' => !$stock->is_active]);
        
        return response()->json([
            'success' => true,
            'message' => 'Stock status updated successfully!',
            'is_active' => $stock->is_active
        ]);
    }
    
    // Update transaction status
    public function updateTransactionStatus(Request $request, $transactionId)
    {
        $transaction = StockTransaction::findOrFail($transactionId);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled,failed'
        ]);
        
        $transaction->update($validated);
        
        return redirect()->back()
            ->with('success', 'Transaction status updated successfully!');
    }
    
    // Analytics and Reports
    public function analytics()
    {
        // Investment performance over time
        $monthlyData = Investment::select(
                DB::raw('DATE_FORMAT(updated_at, "%Y-%m") as month'),
                DB::raw('SUM(total_invested) as invested'),
                DB::raw('SUM(current_value) as value'),
                DB::raw('SUM(total_profit_loss) as profit')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();
        
        // Transaction volume by month
        $transactionVolume = StockTransaction::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as volume')
            )
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();
        
        return view('admin.investments.analytics', compact(
            'monthlyData',
            'transactionVolume'
        ));
    }

    // Export Investments
public function exportInvestments()
{
    $investments = Investment::with('user')->get();
    
    $filename = 'investments_' . now()->format('Y-m-d_His') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];
    
    $callback = function() use ($investments) {
        $file = fopen('php://output', 'w');
        
        // Headers
        fputcsv($file, [
            'User Name',
            'Email',
            'Total Invested',
            'Current Value',
            'Profit/Loss',
            'Profit/Loss %',
            'Stocks Owned',
            'Last Updated'
        ]);
        
        // Data
        foreach ($investments as $investment) {
            fputcsv($file, [
                $investment->user->name,
                $investment->user->email,
                $investment->total_invested,
                $investment->current_value,
                $investment->total_profit_loss,
                $investment->profit_loss_percentage,
                $investment->user->userStocks()->count(),
                $investment->updated_at->format('Y-m-d H:i:s')
            ]);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}

// Export Transactions
public function exportTransactions()
{
    $transactions = StockTransaction::with(['user', 'stock'])->get();
    
    $filename = 'transactions_' . now()->format('Y-m-d_His') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];
    
    $callback = function() use ($transactions) {
        $file = fopen('php://output', 'w');
        
        // Headers
        fputcsv($file, [
            'Transaction ID',
            'User Name',
            'Email',
            'Stock Symbol',
            'Stock Name',
            'Type',
            'Quantity',
            'Price Per Share',
            'Total Amount',
            'Status',
            'Date'
        ]);
        
        // Data
        foreach ($transactions as $transaction) {
            fputcsv($file, [
                $transaction->id,
                $transaction->user->name,
                $transaction->user->email,
                $transaction->stock->symbol,
                $transaction->stock->name,
                ucfirst($transaction->transaction_type),
                $transaction->quantity,
                $transaction->price_per_share,
                $transaction->total_amount,
                ucfirst($transaction->status),
                $transaction->created_at->format('Y-m-d H:i:s')
            ]);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}

// Export Analytics
public function exportAnalytics()
{
    // Investment performance
    $monthlyData = Investment::select(
            DB::raw('DATE_FORMAT(updated_at, "%Y-%m") as month'),
            DB::raw('SUM(total_invested) as invested'),
            DB::raw('SUM(current_value) as value'),
            DB::raw('SUM(total_profit_loss) as profit')
        )
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->take(12)
        ->get();
    
    // Transaction volume
    $transactionVolume = StockTransaction::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(total_amount) as volume')
        )
        ->where('status', 'completed')
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->take(12)
        ->get();
    
    $filename = 'analytics_report_' . now()->format('Y-m-d_His') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];
    
    $callback = function() use ($monthlyData, $transactionVolume) {
        $file = fopen('php://output', 'w');
        
        // Investment Performance Section
        fputcsv($file, ['INVESTMENT PERFORMANCE - LAST 12 MONTHS']);
        fputcsv($file, ['Month', 'Total Invested', 'Current Value', 'Profit/Loss', 'ROI %']);
        
        foreach ($monthlyData as $data) {
            $roi = $data->invested > 0 ? (($data->profit / $data->invested) * 100) : 0;
            fputcsv($file, [
                \Carbon\Carbon::parse($data->month . '-01')->format('M Y'),
                number_format($data->invested, 2),
                number_format($data->value, 2),
                number_format($data->profit, 2),
                number_format($roi, 2) . '%'
            ]);
        }
        
        // Empty line
        fputcsv($file, []);
        
        // Transaction Volume Section
        fputcsv($file, ['TRANSACTION VOLUME - LAST 12 MONTHS']);
        fputcsv($file, ['Month', 'Transactions', 'Volume', 'Avg Transaction']);
        
        foreach ($transactionVolume as $volume) {
            $avgTransaction = $volume->count > 0 ? $volume->volume / $volume->count : 0;
            fputcsv($file, [
                \Carbon\Carbon::parse($volume->month . '-01')->format('M Y'),
                number_format($volume->count),
                number_format($volume->volume, 2),
                number_format($avgTransaction, 2)
            ]);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}
}
