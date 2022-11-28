<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Invoice {{$invoice->invoice_number}}</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        body {
            background-color: rgb(238, 238, 238);
        }

        .container {
            flex-direction: column;
        }

        .container-top {
            background-color: rgb(64, 112, 255);
            height: 50vh;
        }

        .logo {
            position: relative;
            display: inline-block;
            align-items: center;
            top: 30px;
            left: 50px;
        }

        .image-logo {
            max-height: 30px;
        }

        .container-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: -250px;
        }

        .card {
            box-shadow: 0px 1px 3px 0px rgb(54 74 99 / 5%);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background-color: rgb(255, 255, 255);
            width: 60%;
            height: auto;
            padding: 30px;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-clip: border-box;
            border: 0 solid rgba(0, 0, 0, 0.125);
        }

        .card-title {
            margin-bottom: 40px;
        }

        .card-image {
            width: 150px;
            height: 150px;
        }

        .card-body-top {
            width: 80%;
            margin-inline: 30px;
            margin-top: 2.75rem;
            margin-bottom: 1rem;
            font-size: 14px;
            line-height: 28px;
            color: #000000;
        }

        .border-bottom {
            width: 80%;
            border-bottom: 3px solid #000;
            margin: 50px 0;
        }

        .head-invoice {
            width: 80%;
            display: flex;
            justify-content: space-between;
        }

        .head-invoice-right {
            width: 40%;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .text {
            font-weight: 600;
            font-size: 14px;
            line-height: 21px;
            color: #303030;
        }

        .text-primary {
            font-weight: 700;
            font-size: 14px;
            line-height: 21px;
            color: #4070FF;
        }

        .text-normal {
            font-weight: 400;
            font-size: 14px;
            line-height: 21px;
            color: #303030;
        }

        .text-danger {
            color: #FF7D7D;
        }

        .table-content {
            margin: 30px 0;
            width: 80%;
        }

        .title-table {
            font-weight: 700;
            font-size: 14px;
            line-height: 21px;
        }

        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0 7px;
            width: 100%;
            padding: 20px;
        }

        .table thead tr {
            height: 40px;
        }

        .table thead tr th,
        .table tbody tr td {
            border-bottom: 1px solid #ccc;
            text-align: start;
            padding-left: 20px;
        }

        .table thead tr th {
            font-weight: 500;
            font-size: 14px;
            line-height: 21px;
            color: #000000;
        }

        .table tbody tr {
            height: 60px;
        }

        .table tbody tr td {
            font-weight: 700;
            font-size: 12px;
            line-height: 18px;
            color: #373737;
        }

        .content-total {
            width: 80%;
            display: flex;
            justify-content: end;
        }

        .content {
            width: 50%;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .content-left p {
            margin-bottom: 15px;
        }

        .content-right {
            width: 25%;
        }

        .content-right p {
            margin-bottom: 15px;
        }

        .text-left {
            font-weight: 400;
            font-size: 14px;
            line-height: 21px;
        }

        .text-right {
            font-weight: 700;
            font-size: 14px;
            line-height: 21px;
            color: #373737;
        }

        .text-left-total,
        .text-right-total {
            font-weight: 700;
            font-size: 18px;
            line-height: 27px;
            color: #000000;
        }

        .card-body-bottom {
            width: 80%;
            margin-inline: 30px;
            align-items: center;
            text-align: left;
            margin-top: 1rem;
            font-weight: 400;
            font-size: 14px;
            line-height: 28px;
            color: #000000;
        }

        .card-link-button {
            margin-top: 100px;
            text-decoration: none;
        }

        .card-button {
            cursor: pointer;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            padding: 16px 40px;
            gap: 10px;
            width: 210px;
            height: 50px;
            background: #4070FF;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            line-height: 18px;
            color: #fff;
            border: 1px solid transparent;
        }

        .footer {
            width: 100%;
            position: relative;
            bottom: 0;
            left: 0;
            padding-bottom: 20px;
        }

        .content-footer {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-weight: 400;
            font-size: 16px;
            line-height: 24px;
            text-align: center;
            color: rgb(204, 204, 204);
        }

        .footer-logo {
            width: 100px;
            height: 100px;
        }

        @media only screen and (max-width: 992px) {
            .container {
                height: 100vh;
            }

            .card {
                width: 80%;
                height: auto;
                padding: 30px;
            }

            .logo {
                top: 20px;
                left: 10px;
            }

            .card-image {
                width: 100px;
                height: 100px;
            }

            .content {
                width: 100%;
            }

            .content-footer {
                font-size: 12px;
                line-height: 18px;
                text-align: center;
            }

            .footer-logo {
                width: 50px;
                height: 50px;
            }
        }

        @media only screen and (max-width: 330px) {
            .container-content {
                margin-top: -150px;
            }

            .card {
                width: 70%;
            }

            .card-body-top {
                width: 100%;
            }

            .border-bottom {
                width: 100%;
            }

            .head-invoice {
                flex-direction: column;
                justify-content: inherit;
            }

            .head-invoice {
                width: 100%;
            }

            .table-content {
                width: 100%;
            }

            .head-invoice-right {
                width: 100%;
                margin-top: 10px;
            }
        }
    </style>
</head>

<body>
<div class="container">
    <div class="container-top">
        <a class="logo" href="#">
            <img class="image-logo" srcset="{{asset('assets/mails/invoice/logo.svg')}}" alt="logo"/>
        </a>
    </div>
    <div class="container-content">
        <div class="card">
            <h3 class="card-title">
                Factuur #F-202200007
            </h3>
            <img class="card-image" srcset="{{asset('assets/mails/invoice/banner.svg')}}" alt="EmailIcon"/>
            <p class="card-body-top">
                <span>
                    Beste
                    <b>Simeon Benson</b>, Wij attenderen u erop dat de betalingstermijn van onze factuur met factuurnummer <b>F-202200007</b> verstreken is. Helaas hebben wij nog geen betaling van u ontvangen. Wellicht is de factuur wederom aan uw aandacht ontsnapt. Wij willen u verzoeken het openstaande bedrag van <b>-€450</b> binnen 7 dagen na ontvangst van deze brief aan ons over te maken op rekening <b>910-8290-1878</b>. Wij hebben een kopie van de betreffende factuur meegestuurd. Indien deze mail uw betaling heeft gekruist kunt u deze herinnering als niet verzonden beschouwen. Heeft u naar aanleiding hiervan nog vragen, neem dan contact met ons op. Met vriendelijke groet,
                </span>
            </p>

            <div class="border-bottom"></div>

            <div class="head-invoice">
                <div class="head-invoice-left">
                    <p class="text">To</p>
                    <p class="text-primary">
                        Simeon Benson
                    </p>
                    <p class="text-normal">
                        Plamongan Indah H-12, Kec. Penggaron,
                    </p>
                    <p class="text-normal">
                        Kota Semarang, Jawa Tengah 50263,
                    </p>
                    <p class="text-normal">
                        Semarang
                    </p>
                    <p class="text-normal">
                        50263 Jawa Tengah
                    </p>
                    <p class="text-normal">
                        Indonesia
                    </p>
                </div>
                <div class="head-invoice-right">
                    <div>
                        <p class="text">Payment Method</p>
                        <p class="text">Date</p>
                        <p class="text">Due Date</p>
                    </div>
                    <div>
                        <p class="text-normal">Bank Transfer</p>
                        <p class="text-normal">8/10/2022</p>
                        <p class="text-normal">18/10/2022</p>
                    </div>
                </div>
            </div>

            <div class="table-content">
                <p class="title-table">Work Services</p>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Item & Description</th>
                            <th>Amount</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- {Object.keys(form).map((row, key) => ( -->
                        <tr>
                            <td>Roof Maintenance</td>
                            <td>2</td>
                            <td>m2</td>
                            <td>€100</td>
                            <td>€200</td>
                        </tr>
                        <tr>
                            <td>Roof Painting</td>
                            <td>3</td>
                            <td>m2</td>
                            <td>€100</td>
                            <td>€300</td>
                        </tr>
                        <tr>
                            <td>Roof Repairment</td>
                            <td>2</td>
                            <td>m2</td>
                            <td>€100</td>
                            <td>€200</td>
                        </tr>
                        <!-- ))} -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="content-total">
                <div class="content">
                    <div class="content-left">
                        <p class="text-left">Sub Total</p>
                        <p class="text-left">Discount</p>
                        <p class="text-left">Tax (0%)</p>
                        <p class="text-left-total">Total</p>
                    </div>
                    <div class="content-right">
                        <p class="text-right">€700</p>
                        <p class="text-right">€0</p>
                        <p class="text-right">€0</p>
                        <p class="text-right-total">€700</p>
                    </div>
                </div>
            </div>

            <p class="card-body-bottom">
          <span>
            Pleasure doing business with you
          </span>
            </p>

            <a class="card-link-button" href="#">
                <button class="card-button" type="submit">
                    Download Invoice
                </button>
            </a>
        </div>
        <div class="footer">
            <div class="content-footer">
                <img class="footer-logo" srcset="{{asset('assets/mails/invoice/logo-bottom.svg')}}" alt="logo bottom"/>
                <div>
            <span>© 2022 FlexAvi, All rights reserved.<br/>Burg van Vrijberghestr
              90, Tholen, Zeeland, 06-21509781</span>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>
