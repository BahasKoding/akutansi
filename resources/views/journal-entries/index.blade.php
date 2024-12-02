@extends('main')
@section('title', $title)

@push('styles')
<style>
    .report-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        color: white;
    }
    .stat-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .amount-cell {
        font-family: 'Roboto Mono', monospace;
        font-weight: 500;
    }
    .filter-card {
        border: none;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.9);
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="report-header shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 mb-2">Journal Entries</h1>
                <p class="mb-0 opacity-75">View all accounting journal entries</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-muted">Total Debit</div>
                        <div class="icon-shape bg-primary bg-opacity-10 rounded-circle p-2">
                            <i class="bi bi-arrow-left-circle text-primary"></i>
                        </div>
                    </div>
                    <h3 class="mb-0 amount-cell">Rp {{ number_format($totalDebit ?? 0, 0, ',', '.') }}</h3>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-muted">Total Credit</div>
                        <div class="icon-shape bg-info bg-opacity-10 rounded-circle p-2">
                            <i class="bi bi-arrow-right-circle text-info"></i>
                        </div>
                    </div>
                    <h3 class="mb-0 amount-cell">Rp {{ number_format($totalCredit ?? 0, 0, ',', '.') }}</h3>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-info" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-muted">Total Entries</div>
                        <div class="icon-shape bg-success bg-opacity-10 rounded-circle p-2">
                            <i class="bi bi-journal-text text-success"></i>
                        </div>
                    </div>
                    <h3 class="mb-0">{{ number_format($totalEntries ?? 0) }}</h3>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card filter-card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="table-light">
                            <th scope="col" class="text-uppercase text-muted small">Date</th>
                            <th scope="col" class="text-uppercase text-muted small">Transaction</th>
                            <th scope="col" class="text-uppercase text-muted small">Description</th>
                            <th scope="col" class="text-uppercase text-muted small">Debit Account</th>
                            <th scope="col" class="text-uppercase text-muted small">Credit Account</th>
                            <th scope="col" class="text-uppercase text-muted small text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($journalEntries as $entry)
                        <tr>
                            <td class="text-muted">{{ $entry->created_at->format('d M Y') }}</td>
                            <td>
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success">
                                    {{ ucfirst($entry->transaction->type) }}
                                </span>
                            </td>
                            <td>{{ $entry->transaction->description }}</td>
                            <td>
                                <span class="text-dark">{{ $entry->debitAccount->name }}</span>
                            </td>
                            <td>
                                <span class="text-dark">{{ $entry->creditAccount->name }}</span>
                            </td>
                            <td class="text-end amount-cell">
                                <span class="fw-medium">Rp {{ number_format($entry->amount, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-journal-text display-6 d-block mb-3"></i>
                                    <p class="mb-0">No journal entries found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
