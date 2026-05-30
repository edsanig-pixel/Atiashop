@extends('layouts.app')

@section('title', 'مدیریت محصولات')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">مدیریت محصولات</h4>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        افزودن محصول
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">

        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ردیف</th>
                    <th>نام</th>
                    <th>کد شناسایی کالا</th>
                    <th>خرید</th>
                    <th>فروش</th>
                    <th>موجودی</th>
                    <th class="text-center">عملیات</th>
                </tr>
            </thead>
            <tbody>

            @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ number_format($product->purchase_price) }}</td>
<td>{{ number_format($product->sale_price) }}</td>

                    <td>
                        @if($product->stock < 5)
                            <span class="badge bg-danger">{{ $product->stock }}</span>
                        @else
                            <span class="badge bg-success">{{ $product->stock }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                            ویرایش
                        </a>

                        <form action="{{ route('products.destroy', $product) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('حذف شود؟')">
                                حذف
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center p-4">
                        هیچ محصولی ثبت نشده
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>

    </div>
</div>

<div class="mt-3">
    {{ $products->links() }}
</div>

@endsection
