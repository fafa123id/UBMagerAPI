<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Receipt</title>
<style>
body{
    font-family: DejaVu Sans, sans-serif;
    background:#0f172a;
    color:#e5e7eb;
    padding:40px;
}
.card{
    background:#020617;
    border-radius:16px;
    padding:30px;
    border:1px solid #1e293b;
}
.header{
    display:flex;
    justify-content:space-between;
    margin-bottom:30px;
}
.logo{
    font-size:26px;
    font-weight:bold;
    color:#38bdf8;
}
.badge{
    background:#16a34a;
    padding:6px 12px;
    border-radius:8px;
    font-size:12px;
}
.section{
    margin-top:25px;
}
.table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}
.table th{
    text-align:left;
    padding:10px;
    background:#020617;
    border-bottom:1px solid #334155;
}
.table td{
    padding:10px;
    border-bottom:1px solid #1e293b;
}
.total{
    text-align:right;
    font-size:22px;
    margin-top:20px;
    color:#38bdf8;
}
.footer{
    margin-top:40px;
    font-size:12px;
    text-align:center;
    color:#94a3b8;
}
</style>
</head>

<body>
<div class="card">

    <div class="header">
        <div>
            <div class="logo">UBMager</div>
            <div>Receipt / Kuitansi</div>
        </div>
        <div class="badge">PAID</div>
    </div>

    <div>
        <strong>Receipt ID:</strong> {{ $order->transaction->receipt }} <br>
        <strong>Date:</strong> {{ $order->transaction->updated_at->format('d M Y H:i') }} <br>
        <strong>Payment:</strong> {{ $order->transaction->payment_method }}
    </div>

    <div class="section">
        <strong>Customer</strong><br>
        {{ $order->user->name }} <br>
        {{ $order->user->email }}
    </div>

    <div class="section">
        <strong>Seller</strong><br>
        {{ $order->product->user->name }}
    </div>

    <div class="section">
        <strong>Order Detail</strong>

        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $order->product->name }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>Rp {{ number_format($order->product->price) }}</td>
                    <td>Rp {{ number_format($order->product->price * $order->quantity) }}</td>
                </tr>
                <tr>
                    <td>Biaya Aplikasi</td>
                    <td>1</td>
                    <td>Rp 2.000</td>
                    <td>Rp 2.000</td>
                </tr>
                <tr>
                    <td>Biaya Lain</td>
                    <td>1</td>
                    <td>Rp 1.000</td>
                    <td>Rp 1.000</td>
                </tr>
            </tbody>
        </table>

        <div class="total">
            TOTAL : Rp {{ number_format($order->total_price) }}
        </div>
    </div>

    <div class="footer">
        Terima kasih telah berbelanja di UBMager ❤️ <br>
        Dokumen ini adalah bukti pembayaran yang sah.
    </div>

</div>
</body>
</html>