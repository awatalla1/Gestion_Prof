<?php

namespace App\Http\Livewire;

use App\Models\IssueBook;
use Livewire\Component;

class Report extends Component
{
    public $dateReport;
    public $monthReport;
    public $reports;
   
    public function render()
    {
        return view('livewire.report')->layout('layout.app');
    }

    public function index()
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;

        return [
            'daily' => [
                'pending_orders' => Order::whereDate('created_at', $today)
                                    ->whereIn('status', ['pending', 'processing'])
                                    ->count(),
                'completed_orders' => Order::whereDate('created_at', $today)
                                    ->where('status', 'completed')
                                    ->count(),
                'revenue' => Payment::whereDate('payment_date', $today)
                                ->sum('amount')
            ],
            'monthly' => [
                'orders_by_month' => $this->getMonthlyOrders(),
                'books_by_category' => $this->getBooksByCategory($currentMonth)
            ]
        ];
    }

    protected function getMonthlyOrders()
    {
        return Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month');
    }

    protected function getBooksByCategory($month)
    {
        return Book::select('category', DB::raw('SUM(order_items.quantity) as total'))
            ->join('order_items', 'books.id', '=', 'order_items.book_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->whereMonth('orders.created_at', $month)
            ->groupBy('category')
            ->get();
    }
    
}
