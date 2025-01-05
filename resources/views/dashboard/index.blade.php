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
            @if (auth()->user()->role === 'owner')
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
            @endif

            @if (auth()->user()->role === 'finance')
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-primary bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-calendar-check text-primary fs-3"></i>
                                </div>
                            </div>
                            <h5 class="text-muted mb-1">Today's Income</h5>
                            <h2 class="mb-0 fw-bold amount-cell">Rp {{ number_format($todayIncome, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-success bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-graph-up text-success fs-3"></i>
                                </div>
                            </div>
                            <h5 class="text-muted mb-1">Weekly Income</h5>
                            <h2 class="mb-0 fw-bold amount-cell">Rp {{ number_format($weeklyIncome, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>
            @endif

            @if (auth()->user()->role === 'admin')
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-primary bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-wallet2 text-primary fs-3"></i>
                                </div>
                            </div>
                            <h5 class="text-muted mb-1">Total Accounts</h5>
                            <h2 class="mb-0 fw-bold">{{ $totalAccounts }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-success bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-tags text-success fs-3"></i>
                                </div>
                            </div>
                            <h5 class="text-muted mb-1">Total Categories</h5>
                            <h2 class="mb-0 fw-bold">{{ $totalCategories }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-info bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-people text-info fs-3"></i>
                                </div>
                            </div>
                            <h5 class="text-muted mb-1">Total Users</h5>
                            <h2 class="mb-0 fw-bold">{{ $totalUsers }}</h2>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Charts Section -->
        @if (in_array(auth()->user()->role, ['owner', 'finance']))
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
        @endif

        @if (auth()->user()->role === 'admin')
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                            <h5 class="mb-0">Account Types Distribution</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($accountTypes as $type)
                                            <tr>
                                                <td>{{ ucfirst($type->type) }}</td>
                                                <td>{{ $type->count }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        @if (in_array(auth()->user()->role, ['owner', 'finance']))
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const monthlyData = {!! json_encode($monthlyData) !!};
                    const locale = window.navigator.language;

                    const labels = monthlyData.map(data => {
                        const startDate = new Date(data.start_date);
                        const endDate = new Date(data.end_date);
                        return `${startDate.toLocaleDateString(locale, { day: 'numeric' })}-${endDate.toLocaleDateString(locale, { day: 'numeric' })} ${endDate.toLocaleDateString(locale, { month: 'short' })}`;
                    });
                    const incomeData = monthlyData.map(data => data.income);

                    const ctx = document.getElementById('incomeChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Weekly Income',
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
                                },
                                title: {
                                    display: true,
                                    text: 'Weekly Income Overview'
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
                                            return new Intl.NumberFormat(locale, {
                                                style: 'currency',
                                                currency: 'IDR',
                                                minimumFractionDigits: 0,
                                                maximumFractionDigits: 0
                                            }).format(value);
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
        @endif
    @endpush

@endsection
