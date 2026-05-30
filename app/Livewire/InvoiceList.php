<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Invoice;
use Livewire\WithPagination;

class InvoiceList extends Component
{
    use WithPagination;

    public $showDeleteModal = false;
    public $deleteId;

    public function delete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        $invoice = Invoice::with('items')->find($this->deleteId);
        if ($invoice) {
            // برگرداندن موجودی
            foreach ($invoice->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }
            $invoice->delete();
            session()->flash('message', 'فاکتور حذف شد و موجودی برگشت داده شد.');
        }
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function render()
    {
        $invoices = Invoice::with('customer')->orderBy('created_at', 'desc')->paginate(15);
        return view('livewire.invoice-list', compact('invoices'))->layout('layouts.app');
    }
}