<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Invoice;

class SalesReport extends Component
{
    public $total_today = 0;
    public $total_month = 0;

    public function mount()
    {
        $today = now()->toDateString();
        $this->total_today = Invoice::whereDate('created_at', $today)->sum('total_amount');
        $this->total_month = Invoice::whereMonth('created_at', now()->month)->sum('total_amount');
    }

    public function render()
    {
        return view('livewire.sales-report')->layout('layouts.app');
    }
}