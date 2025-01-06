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
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="report-header shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h3 mb-2">Transactions</h1>
                    <p class="mb-0 opacity-75">Manage your financial transactions</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <button type="button" class="btn btn-light" data-bs-toggle="modal"
                        data-bs-target="#createTransactionModal">
                        <i class="bi bi-plus-circle me-1"></i> New Transaction
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="text-muted">Total Income</div>
                            <div class="icon-shape bg-success bg-opacity-10 rounded-circle p-2">
                                <i class="bi bi-arrow-up-circle text-success"></i>
                            </div>
                        </div>
                        <h3 class="mb-0 amount-cell">Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}</h3>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-6">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="text-muted">Net Balance</div>
                            <div class="icon-shape bg-primary bg-opacity-10 rounded-circle p-2">
                                <i class="bi bi-wallet2 text-primary"></i>
                            </div>
                        </div>
                        <h3 class="mb-0 amount-cell">Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}</h3>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Income Transactions -->
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">
                            <i class="bi bi-arrow-up-circle text-success me-2"></i>Income Transactions
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Account</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $transaction->account->name }}</td>
                                        <td>{{ $transaction->type }}</td>
                                        <td class="amount-cell">Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                        </td>
                                        <td>{{ $transaction->category->name }}</td>
                                        <td>{{ $transaction->description }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-light edit-transaction"
                                                data-transaction-id="{{ $transaction->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST"
                                                class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-3">
                                            No income transactions found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Transaction Modal -->
    <div class="modal fade" id="createTransactionModal" tabindex="-1" aria-labelledby="createTransactionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTransactionModalLabel">Create New Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="income">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Account</label>
                            <select class="form-select" name="account_id" required>
                                <option disabled selected>Choose Account</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} | Rp
                                        {{ number_format($account->balance ?? 0, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" class="form-control" name="amount" required min="0"
                                placeholder="Input Amount">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category_id" required>
                                <option disabled selected>Choose Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Optional"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Transaction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Transaction Modal -->
    <div class="modal fade" id="editTransactionModal" tabindex="-1" aria-labelledby="editTransactionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTransactionModalLabel">Edit Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editTransactionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Akun</label>
                            <select class="form-select" name="account_id" id="edit_account_id" required>
                                <option disabled selected>Pilih Akun</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} | Rp
                                        {{ $account->balance }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="text" class="form-control" name="amount" id="edit_amount" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="category_id" id="edit_category_id" required>
                                <option disabled selected>Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3" placeholder="Opsional"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Format number to Indonesian format (with thousand separator)
                function formatNumber(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                }

                // Convert formatted string back to number
                function unformatNumber(formattedString) {
                    return formattedString.replace(/[^\d,-]/g, '').replace(',', '.');
                }

                // Handle edit button clicks
                document.querySelectorAll('.edit-transaction').forEach(button => {
                    button.addEventListener('click', function() {
                        const transactionId = this.dataset.transactionId;

                        fetch(`/transactions/${transactionId}/edit`)
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById('edit_account_id').value = data.transaction
                                    .account_id;
                                // Format the amount properly when loading
                                document.getElementById('edit_amount').value = formatNumber(data
                                    .transaction.amount);
                                document.getElementById('edit_category_id').value = data.transaction
                                    .category_id;
                                document.getElementById('edit_description').value = data.transaction
                                    .description || '';

                                document.getElementById('editTransactionForm').action =
                                    `/transactions/${transactionId}`;
                                new bootstrap.Modal(document.getElementById('editTransactionModal'))
                                    .show();
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan saat mengambil data transaksi!'
                                });
                            });
                    });
                });

                // Format amount input while typing
                const amountInputs = document.querySelectorAll('input[name="amount"]');
                amountInputs.forEach(input => {
                    input.addEventListener('input', function(e) {
                        // Remove any non-numeric characters except comma and dot
                        let value = this.value.replace(/[^\d.,]/g, '');

                        // Convert to plain number first
                        value = unformatNumber(value);

                        // Format with thousand separator
                        if (value) {
                            this.value = formatNumber(value);
                        }
                    });
                });

                // Handle form submission
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        const amountInput = this.querySelector('input[name="amount"]');
                        if (amountInput) {
                            // Convert the formatted number back to a plain number before submission
                            const plainNumber = unformatNumber(amountInput.value);
                            amountInput.value = plainNumber;
                        }
                    });
                });

                // Show success message if exists
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}'
                    });
                @endif
            });
        </script>
    @endpush

@endsection
