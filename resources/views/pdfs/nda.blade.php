<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>NDA</title>
    <style>
        @page {
            margin: 28px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
            line-height: 1.55;
        }

        .header {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 18px 18px 14px;
            margin-bottom: 16px;
        }

        .brand {
            font-size: 11px;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 6px;
        }

        h1 {
            margin: 0 0 6px 0;
            font-size: 20px;
            letter-spacing: .02em;
        }

        .meta {
            font-size: 11px;
            color: #6b7280;
        }

        .pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: #eef2ff;
            color: #3730a3;
            font-weight: 700;
            font-size: 11px;
            margin-left: 8px;
        }

        .grid {
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .row {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .cell {
            display: table-cell;
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .cell.label {
            width: 28%;
            background: #f9fafb;
            color: #374151;
            font-weight: 700;
        }

        .cell.value {
            width: 72%;
        }

        .section {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 14px;
        }

        .section h2 {
            margin: 0 0 8px 0;
            font-size: 13px;
            color: #111827;
        }

        .section p {
            margin: 0;
            color: #111827;
        }

        ol {
            margin: 0;
            padding-left: 18px;
        }

        ol li {
            margin: 0 0 8px 0;
        }

        .muted {
            color: #6b7280;
        }

        .sign-wrap {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 14px 16px;
            margin-top: 12px;
        }

        .sign-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .sign-grid td {
            padding: 10px 10px 16px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .sign-title {
            font-weight: 700;
            margin-bottom: 6px;
        }

        .line {
            height: 1px;
            background: #111827;
            opacity: .25;
            margin-top: 28px;
        }

        .footer {
            margin-top: 10px;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="brand">Mergersales</div>
        <h1>Non-Disclosure Agreement <span class="pill">NDA</span></h1>
        <div class="meta">
            Date: {{ $date }} &nbsp; | &nbsp; Reference: ENQ-{{ $enquiry->id }}
        </div>
    </div>

    <div class="grid">
        <div class="row">
            <div class="cell label">Seller</div>
            <div class="cell value">{{ $sellerName }}</div>
        </div>
        <div class="row">
            <div class="cell label">Buyer</div>
            <div class="cell value">{{ $buyerName }}</div>
        </div>
        <div class="row">
            <div class="cell label">Regarding</div>
            <div class="cell value">{{ $dealInfo }}</div>
        </div>
        <div class="row">
            <div class="cell label">Budget</div>
            <div class="cell value">{{ $budget }}</div>
        </div>
    </div>

    <div class="section">
        <h2>Agreement</h2>
        <p>
            This Non-Disclosure Agreement (“Agreement”) is entered into by and between the Seller and Buyer
            for the purpose of evaluating a potential business opportunity. Both parties agree to the following terms.
        </p>
    </div>

    <div class="section">
        <h2>Terms & Conditions</h2>
        <ol>
            <li><b>Confidential Information:</b> Any business, financial, operational, client, supplier, documents, or
                other private information shared by Seller is confidential.</li>
            <li><b>Non-Use & Non-Disclosure:</b> Buyer agrees not to misuse, copy, share, or disclose confidential
                information to any third party without Seller’s written permission.</li>
            <li><b>Purpose:</b> Information will be used only for evaluation of the opportunity and not for any other
                purpose.</li>
            <li><b>Return/Destruction:</b> Upon request, Buyer will return or destroy confidential materials and confirm
                in writing.</li>
            <li><b>Breach:</b> Misuse or disclosure may lead to legal action and remedies available under applicable
                law.</li>
        </ol>
        <p class="muted" style="margin-top:10px;">
            This document is generated electronically; signatures may be physical or electronic as allowed by law.
        </p>
    </div>

    <div class="footer">
        Confidential — For intended recipient only. © {{ date('Y') }} Mergersales
    </div>

</body>

</html>
