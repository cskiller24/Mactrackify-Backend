<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Order Confirmation</title>
    <style>
        body {
            font-family: Times New Roman, sans-serif;
            margin: 20px;
        }
        .box {
            display: flex;
            flex-wrap: wrap;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        h2 {
            color: #333;
        }
        p {
            margin-bottom: 1em;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #5888d0;
        }
    </style>
</head>
<body>

<div class="box">
    <div style="display: flex; justify-content: space-between; width: 100%">
        <div>
            <div>Sold To: <b>{{ $transaction->account->name }}</b></div>
            <div>Address: <b>{{ $transaction->account->address }}</b></div>
        </div>
        <div>
            <div>Date: {{ $transaction->created_at->toDateString() }}</div>
            <div>Salesman: {{ $transaction->user->full_name }}</div>
        </div>
    </div>
</div>
<table>
    <thead>
        <tr>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Description</th>
            <th>Unit Price</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transaction->items as $transactionItem)
        <tr>
            <td>{{ $transactionItem->quantity }}</td>
            <td>{{ $transactionItem->warehouseItem->name }}</td>
            <td>{{ $transactionItem->warehouseItem->description }}</td>
            <td>{{ $transactionItem->warehouseItem->price }}</td>
            <td>{{ $transactionItem->quantity * $transactionItem->warehouseItem->price }}</td>
        </tr>
        @endforeach

        @for($i = 0; $i < $emptyCount; $i++)
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @endfor
        
        <tr>
            <td colspan="4" style="background: lightblue; text-align: center">
                <b>Total Amount</b>
                </td>
            <td><b>{{ $totalAmount }}</b></td>
        </tr>
    </tbody>
</table>

<div class="box">
    <div style="display: flex; justify-content: space-between; width: 100%">
        <div>
            <div>Prepared By: <u><b>{{ $transaction->user->full_name }}</b></u></div>
        </div>
        <div>
            <div>Checked By: <u><b>{{ $transaction->account->name }}</b></u></div>
        </div>
        <div>
            <div>Approved By: <u><b>{{ $deployer->full_name }}</b></u></div>
        </div>
    </div>
</div>



</body>
</html>
