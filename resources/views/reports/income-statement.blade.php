@extends('main')
@section('title', 'Report')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        .card-outline {
            border-top: 3px solid #007bff;
        }

        .date-range-container {
            background: #f8f9fa;
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Filter Card -->
                <div class="card card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Filter Options</h3>
                    </div>
                    <div class="card-body">
                        <form id="filterForm" class="row align-items-end">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date" name="start_date" id="start_date"
                                        value="{{ request('start_date') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <a href="{{ route('reports.income-statement') }}" class="btn btn-default">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-download"></i> Export
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('preview.income.statement', request()->all()) }}"
                                                    target="_blank">
                                                    <i class="fas fa-eye"></i> Preview PDF
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('download.income.statement', request()->all()) }}">
                                                    <i class="fas fa-file-pdf"></i> Download PDF
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Data Card -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Transaction List</h3>
                        @if (request('start_date') && request('end_date'))
                            <div class="date-range-container p-2 mt-2">
                                <i class="fas fa-calendar"></i>
                                Period: {{ Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} -
                                {{ Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                            </div>
                        @endif
                    </div>
                    <div class="card-body table-responsive p-0">
                        @if ($transactions->count() > 0)
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Account</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success">
                                                    {{ $transaction->category->name }}
                                                </span>
                                            </td>
                                            <td>{{ $transaction->account->name }}</td>
                                            <td class="text-right">
                                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total Income</strong></td>
                                        <td class="text-right">
                                            <strong>Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No transactions found for the selected period.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#start_date", {
            dateFormat: "Y-m-d"
        });
        flatpickr("#end_date", {
            dateFormat: "Y-m-d"
        });
    </script>
@endpush
