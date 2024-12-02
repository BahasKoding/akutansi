<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Income Statement</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            padding: 2rem;
            color: #2d3748;
            line-height: 1.5;
        }

        /* Header styles */
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .header p {
            opacity: 0.9;
            font-size: 14px;
        }

        /* Company Info */
        .company-info {
            margin-bottom: 2rem;
            padding: 1rem;
            background-color: #f8fafc;
            border-radius: 8px;
        }

        .company-info h2 {
            color: #4f46e5;
            font-size: 18px;
            margin-bottom: 0.5rem;
        }

        /* Table styles */
        .table-container {
            margin-top: 2rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        th {
            background-color: #f1f5f9;
            color: #4a5568;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-size: 13px;
        }

        /* Amount column */
        .amount {
            text-align: right;
            font-family: 'Courier New', monospace;
        }

        /* Footer total row */
        .total-row {
            background-color: #f8fafc;
            font-weight: bold;
        }

        .total-row td {
            border-top: 2px solid #e2e8f0;
            color: #1a202c;
            font-size: 14px;
        }

        /* Alternating row colors */
        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Status badges */
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-income {
            background-color: #dcfce7;
            color: #166534;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Income Statement</h1>
        <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    <div class="company-info">
        <h2>Financial Summary</h2>
        <p>Total Income: Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 20%">Date</th>
                    <th style="width: 25%">Category</th>
                    <th style="width: 35%">Account</th>
                    <th style="width: 20%" class="amount">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="badge badge-income">
                            {{ $transaction->category->name }}
                        </span>
                    </td>
                    <td>{{ $transaction->account->name }}</td>
                    <td class="amount">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right"><strong>Total Income</strong></td>
                    <td class="amount"><strong>Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
