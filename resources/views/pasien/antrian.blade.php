@extends('../layouts/base')
@section('body')

    <body>
        <div
            style="padding: 32px 16px 48px; margin: 0 auto; background: #FAFBFD; max-width: 568px; font-size: 16px; line-height: 24px; font-family: 'Poppins', sans-serif;">
            <table align="center" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 600px;">
                <thead>
                    <tr>
                        <th style="padding-bottom: 32px;">
                            Visio Mandiri Medika
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <table align="center" cellpadding="0" cellspacing="0"
                                style="width: 100%; max-width: 600px; background: #FFF; border: 1px solid #C9CED7; border-radius: 4px; font-size: 16px; line-height: 24px; font-family: 'Poppins', sans-serif;"">
                                <tr>
                                    <td style="padding: 7%; text-align: center;">
                                        <p>{{ CarbonParse($data->tanggal, 'd F Y') }}</p>
                                        <p>Nomor Antrian</p>
                                        <p style="font-size: 28px;line-height: 1.5em;font-weight: bold;">
                                            {{ $data->no_reservasi }}</p>
                                        {{ QrCode::size(175)->generate($data->no_reservasi) }}
                                        <p style="margin-top: 2rem">Harap ada dilokasi agar antrian tidak terlewati</p>
                                        <p>Terima kasih</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
@endsection

@section('script')
    <script></script>
@endsection
