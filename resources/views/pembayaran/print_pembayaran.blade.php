<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>E-INVOICE VISIO</title>
    <style>
        * {
            font-family: Gotham, sans-serif;
            font-size: 12px;
        }

        th {
            border: 1 px solid #f5f5f5;
            padding: 10px 10px;
        }

        .main td {
            padding: 10px 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <table style="width: 100%">
        <tr>
            <td>
                <img width="100" src="https://user-images.githubusercontent.com/56387755/176904318-20fdfb9d-aa19-4112-b681-5a3b0db332fb.png">
            </td>
            <td style="text-align: right;font-size: 24px">

                <b style="color: green;font-size: 24px">PAID</b>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br>
                <br>
            </td>
        </tr>
        <tr>
            <td>
                INVOICE
            </td>
        </tr>
        <tr>
            <td>
                <b>#{{ $data->nomor_invoice }}</b>
            </td>
        </tr>
    </table>
    <br>
    <hr style="border: 0.1px solid grey">
    <table style="width: 100%">
        <tr>
            <td style="text-align: left"><b>Ditagihkan ke</b></td>
            <td style="text-align: right"><b>Dibayar Ke</b></td>
        </tr>
        <tr>
            <td style="text-align: left;vertical-align: top">
                <br>
                {{ $data->pasien->name }}<br>
                Kode Pasien :<br>
                {{ $data->pasien ? $data->pasien->id_pasien ?? '-' : '-' }} <br>
                Alamat :<br>
                {{ $data->pasien ? $data->pasien->alamat : '-' }}<br>
                Telpon :<br>
                {{ $data->pasien ? $data->pasien->telp : '-' }}<br>
            </td>
            <td style="text-align: right;vertical-align: top">
                <br>
                Visio Mandiri Medika<br>
                Alamat :<br>
                Jalan Kedung Rukem<br>
                Telpon :<br>
                089898989<br>
            </td>
        </tr>
    </table>
    <hr style="border: 0.1px solid grey">
    <table style="width: 100%">
        <tr>
            <td style="text-align: left"><b>Tanggal Invoice</b></td>
        </tr>
        <tr>
            <td style="text-align: left">{{ carbon\carbon::parse($data->tanggal)->format('d/m/Y') }}</td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;border: 1px solid grey;border-radius: 5px;" class="main">
        <thead style="background: #f5f5f5">
            <tr>
                <th style="width: 5%;text-align: center">NO</th>
                <th>ITEM</th>
                <th style="text-align: right">HARGA</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->pembayaran_detail as $i => $d)
                <tr>
                    <td  style="width: 5%;text-align: center">{{ $i + 1 }}</td>
                    <td>{{ $d->item->name }}</td>
                    <td style="text-align: right">{{ number_format($d->total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table style="width: 100%;border: 1px solid grey;border-radius: 5px;" class="main">
        <tr>
            <td>Terbilang : {{ terbilang($data->total) }} rupiah</td>

            </td>
        </tr>
    </table>
    <br>
    <table style="width: 60%;float: left">
        <tr>
            <td style="padding: 0px 0px"><b style="font-size: 24px">Info Pembayaran</b></td>
        </tr>
        <tr>
            <td style="padding: 0px 0px">
                <br>
                Ditransfer ke :
            </td>
        </tr>
        <tr>
            <td>
                Visio Mandiri Medika
            </td>
        </tr>
        <tr>
            <td>
                BCA Visio Mandiri Medika <br>
                Jalan Kedung Rukem 1 <br>
                A/C : <b>6100897979</b> <br>
                <br>
            </td>
        </tr>
    </table>
    <table style="width: 40%;border: 1px solid grey;border-radius: 5px;float: right" class="main">
        {{-- <tr>
            <td>Discount</td>
            <td style="text-align: right">
                {{ number_format($data->diskon + $data->diskon_penyesuaian) }}
            </td>
        </tr> --}}
        <tr>;
            <td>Total</td>
            <td style="text-align: right">
                {{ number_format($data->total) }}
            </td>
        </tr>
    </table>
</body>

</html>
