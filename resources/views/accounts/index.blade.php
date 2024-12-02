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
</style>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="report-header shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 mb-2">Accounts</h1>
                <p class="mb-0 opacity-75">Manage your cash and bank accounts</p>
            </div>
            <div class="col-md-6 text-md-end">
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createAccountModal">
                    <i class="bi bi-plus-circle me-1"></i> New Account
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-muted">Total Accounts</div>
                        <div class="icon-shape bg-primary bg-opacity-10 rounded-circle p-2">
                            <i class="bi bi-wallet2 text-primary"></i>
                        </div>
                    </div>
                    <h3 class="mb-0">{{ $totalAccounts }}</h3>
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
                        <div class="text-muted">Total Cash</div>
                        <div class="icon-shape bg-success bg-opacity-10 rounded-circle p-2">
                            <i class="bi bi-cash text-success"></i>
                        </div>
                    </div>
                    <h3 class="mb-0 amount-cell">Rp {{ number_format($totalCash, 0, ',', '.') }}</h3>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-muted">Total Bank</div>
                        <div class="icon-shape bg-info bg-opacity-10 rounded-circle p-2">
                            <i class="bi bi-bank text-info"></i>
                        </div>
                    </div>
                    <h3 class="mb-0 amount-cell">Rp {{ number_format($totalBank, 0, ',', '.') }}</h3>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-info" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Cash Accounts -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">
                        <i class="bi bi-cash text-success me-2"></i>Cash Accounts
                    </h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th class="text-end">Balance</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($accounts->where('type', 'cash') as $account)
                            <tr>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        {{ $account->name }}
                                    </span>
                                </td>
                                <td class="text-end amount-cell">
                                    Rp {{ number_format($account->balance, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light edit-account"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editAccountModal"
                                            data-id="{{ $account->id }}"
                                            data-name="{{ $account->name }}"
                                            data-type="{{ $account->type }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="d-inline delete-form">
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
                                <td colspan="3" class="text-center py-3 text-muted">
                                    No cash accounts found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bank Accounts -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">
                        <i class="bi bi-bank text-info me-2"></i>Bank Accounts
                    </h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th class="text-end">Balance</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($accounts->where('type', 'bank') as $account)
                            <tr>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        {{ $account->name }}
                                    </span>
                                </td>
                                <td class="text-end amount-cell">
                                    Rp {{ number_format($account->balance, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light edit-account"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editAccountModal"
                                            data-id="{{ $account->id }}"
                                            data-name="{{ $account->name }}"
                                            data-type="{{ $account->type }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="d-inline delete-form">
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
                                <td colspan="3" class="text-center py-3 text-muted">
                                    No bank accounts found
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

<!-- Create & Edit Modals -->
<div class="modal fade" id="createAccountModal" tabindex="-1" aria-labelledby="createAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAccountModalLabel">Create New Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('accounts.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select class="form-select" name="type" required>
                            <option value="cash">Cash</option>
                            <option value="bank">Bank</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Initial Balance</label>
                        <input type="text" class="form-control" name="balance" id="balance" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Account Modal -->
<div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAccountModalLabel">Edit Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAccountForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_type" class="form-label">Type</label>
                        <select class="form-select" id="edit_type" name="type" required>
                            <option value="cash">Cash</option>
                            <option value="bank">Bank</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_balance" class="form-label">Balance</label>
                        <input type="text" class="form-control" id="edit_balance" name="balance" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to format number to Rupiah
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }

        // Apply Rupiah formatting to balance inputs
        var balanceInputs = document.querySelectorAll('#balance, #edit_balance');
        balanceInputs.forEach(function(input) {
            input.addEventListener('input', function(e) {
                this.value = formatRupiah(this.value, 'Rp ');
            });
        });

        // Handle form submission for create and edit
        document.querySelectorAll('#createAccountModal form, #editAccountForm').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var balanceInput = this.querySelector('[name="balance"]');
                var numericValue = balanceInput.value.replace(/[^\d]/g, '');
                balanceInput.value = numericValue;
                form.submit();
            });
        });

        // Handle edit button click
        document.querySelectorAll('.edit-account').forEach(function(button) {
            button.addEventListener('click', function() {
                // Get data from data attributes
                const accountId = this.dataset.id;
                const name = this.dataset.name;
                const type = this.dataset.type;

                // Get balance from the row
                const row = this.closest('tr');
                const balanceCell = row.querySelector('.amount-cell');
                const balance = balanceCell ? balanceCell.textContent.trim() : '';

                // Set form values
                const form = document.getElementById('editAccountForm');
                if (form) {
                    form.action = '/accounts/' + accountId;
                    const nameInput = form.querySelector('#edit_name');
                    const typeInput = form.querySelector('#edit_type');
                    const balanceInput = form.querySelector('#edit_balance');

                    if (nameInput) nameInput.value = name;
                    if (typeInput) typeInput.value = type;
                    if (balanceInput) balanceInput.value = balance;
                }
            });
        });

        // Handle delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

        // Show SweetAlert for flash messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                timerProgressBar: true
            });
        @endif
    });
</script>
@endpush

@endsection
