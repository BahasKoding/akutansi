@extends('main')
@section('title', $title)

@push('styles')
    <style>
        .welcome-section {
            background: linear-gradient(135deg, #f6f8ff 0%, #f1f4ff 100%);
        }

        .hover-card {
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .amount-cell {
            font-family: 'Roboto Mono', monospace;
            font-weight: 500;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="welcome-section p-4 rounded-3 shadow-sm border-start border-primary border-4">
                    <h1 class="h3 mb-2">Welcome back, <span class="text-primary">{{ auth()->user()->name }}</span>! 👋</h1>
                    <p class="text-muted mb-0">Here's your financial overview for today.</p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="icon-shape bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-arrow-up-circle text-primary fs-3"></i>
                            </div>
                        </div>
                        <h5 class="text-muted mb-1">Total Income</h5>
                        <h2 class="mb-0 fw-bold amount-cell">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="icon-shape bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-wallet2 text-success fs-3"></i>
                            </div>
                        </div>
                        <h5 class="text-muted mb-1">Current Balance</h5>
                        <h2 class="mb-0 fw-bold amount-cell">Rp {{ number_format($currentBalance, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Income Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                        <h5 class="mb-0">Monthly Income Overview</h5>
                    </div>
                    <div class="card-body px-4">
                        <canvas id="incomeChart" style="height: 300px; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div
                        class="card-header bg-transparent border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Transactions</h5>
                        <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4">Date</th>
                                        <th>Account</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th class="px-4">Category</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTransactions as $transaction)
                                        <tr>
                                            <td class="px-4">{{ $transaction->created_at->format('d M Y') }}</td>
                                            <td>{{ $transaction->account->name }}</td>
                                            <td>
                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                                    {{ ucfirst($transaction->type) }}
                                                </span>
                                            </td>
                                            <td class="amount-cell">Rp
                                                {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                            <td class="px-4">{{ $transaction->category->name }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No recent transactions
                                                found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const monthlyData = {!! json_encode($monthlyData) !!};

                const labels = monthlyData.map(data => months[data.month - 1]);
                const incomeData = monthlyData.map(data => data.income);

                const ctx = document.getElementById('incomeChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Monthly Income',
                            data: incomeData,
                            borderColor: '#0d6efd',
                            backgroundColor: '#0d6efd20',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    display: true,
                                    drawBorder: false
                                },
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush

@endsection
