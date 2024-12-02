@extends('main')
@section('title', 'Income Statement')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 rounded-lg shadow-lg mb-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-white text-3xl">Income Statement</h1>
                <p class="text-white opacity-75">Financial performance report for the selected period</p>
            </div>
            <div>
                <a href="{{ route('download.income.statement', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                   class="inline-flex items-center px-4 py-2 bg-white text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-gray-600">Total Income</h3>
            <h2 class="text-2xl font-bold">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
        </div>
    </div>

    <!-- Income Transactions -->
    <div class="bg-white p-4 rounded-lg shadow-md">
        <h5 class="text-lg font-semibold">Income Transactions</h5>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transactions->where('type', 'income') as $transaction)
                    <tr>
                        <td class="px-6 py-4">{{ $transaction->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">{{ $transaction->category->name }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-gray-500 py-4">No income transactions found</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-100">
                <tr>
                    <th colspan="2" class="text-right">Total Income</th>
                    <td class="text-right font-bold">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
