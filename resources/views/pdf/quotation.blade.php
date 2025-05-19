<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin-left: 72px;
            /* Adjust the value to your preference */
        }

        .header-table {
            width: 100%;
            border: none;
        }

        .header-table td {
            vertical-align: top;
            border: none;
        }

        .header-left,
        .header-right {
            width: 30%;
        }

        .header-center {
            text-align: center;
            width: 40%;
        }

        .header-logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            /* Ensures the image maintains its aspect ratio and fills the circle */
        }

        .header-center h5 {
            margin: 0;
        }

        .separator {
            border-top: 3px solid black;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .invoice-header {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .invoice-info {
            width: 100%;
            margin-bottom: 20px;
        }

        .invoice-info td {
            border: none;
        }

        .bill-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .invoice-table {
            margin-top: 10px;
            border: 1px solid black;
            border-collapse: collapse;
            width: 100%;
        }

        .invoice-table th,
        .invoice-table td {
            text-align: center;
            border: 1px solid black;
            padding: 8px;
        }

        .invoice-table .particulars-column {
            text-align: left;
            /* Override alignment for Particulars column */
        }

        .total-row {
            font-weight: bold;
        }

        .description {
            text-align: left;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td class="header-left">
                <p>{{$general->law_firm_lawyer ?? ''}}</p>
            </td>
            <td class="header-center">

                <img src="{{ $logo }}" alt="Logo" class="header-logo">
                <h5>ADVOCATE</h5>
            </td>
            <td class="header-right">
                <p>{{$general->address}}<br>Mob: {{$general->mobile}}<br>E-mail: {{$general->email}}</p>
            </td>
        </tr>
    </table>

    <div class="separator"></div>

    <table class="invoice-info">
        <tr>
            <td class="col-8">
                <p>To,<br>{{$quotationWithDescriptions->client_name}}
                    <br>Mobile-{{$quotationWithDescriptions->client_mobile}}<br>
                    <!-- <address> -->
                    {{$quotationWithDescriptions->client_address}}
                    <!-- </address> -->
                </p>
            </td>
            <td class="col-4 text-end">
                <p>Date: {{date('d-m-Y')}}<br>Quotation #: {{$quotationWithDescriptions->quotation_no}}</p>
            </td>
        </tr>
    </table>

    <div class="bill-title">{{$quotationWithDescriptions->subject}}</div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>#</th>
                <th class="description">Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 0;
            $sum = 0;
            ?>
            @foreach($quotationWithDescriptions->quotation_description as $description)
            <?php
            $counter++;
            $sum += $description['amount'];
            ?>
            <tr>
                <td>{{$counter}}</td>
                <td class="particulars-column">{{$description['description']}}</td>
                <td>Rs. {{$description['amount']}}/-</td>
            </tr>
            @endforeach

        </tbody>
    </table>
    <br>
    <table class="table">
        <tr class="total-row">
            <td colspan="2">Total Rs. {{$sum}}/-</td>
            <td></td>
        </tr>
    </table>

    <!-- <table class="table">
        <tr>
            <td>Location</td>
            <td class="text-end">Law Firm Admin Name</td>
        </tr>
    </table> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
