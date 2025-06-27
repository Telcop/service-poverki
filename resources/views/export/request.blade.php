<!DOCTYPE html>
<html>
<head>
    <title>Заявка №{{ $data['req']['number'] . config('custom.verification_number_request_mask') }} от {{ date('d.m.Y', strtotime($data['req']['date_from'])) }}</title>
   
    <style>
        /* Ваши стили здесь */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .w-a {
            width: 20px;
        }
        .w-b {
            width: 120px;
        }
        .w-c, .w-d{
            width: 80px;
        }
        .w-e{
            width: 70px;
        }
        .w-f{
            width:70px;
        }
        .w_g{
            width:60px;
        }

    </style>
</head>
<body>

 <table>
    {{-- <col width='30'/>
    <col width='137'/>
    <col width='23'/>
    <col width='110'/>
    <col width='100'/>
    <col width='128'/>
    <col width='96'/>
    <col width='103'/>
    <col width='103'/> --}}
    <tbody>
        <tr></tr>
        <tr></tr>
        <tr>
            <td colspan="7">Общество с ограниченной ответственностью «ЭЙ энд ДИ РУС»</td>
            <td colspan="2">ООО "Импульс Лабс"</td>
        </tr>
        <tr>
            <td colspan="7">Юр. адрес: 117556 г. Москва, Варшавское шоссе, д.95, корп.1</td>
            <td colspan="2">420032, г. Казань, ул. Гладилова,</td>
        </tr>
        <tr>
            <td colspan="7">Почтовый адрес: 121357 г. Москва, ул. Верейская, д. 17</td>
            <td colspan="2">дом 27, помещение 1013.</td>
        </tr>
        <tr>
            <td colspan="7">Телефон: +7 (495) 937-3344, факс: +7 (495) 937-5566</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="7">E-mail: info@and-rus.ru, www.and-rus.ru</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="7">ОГРН 1067746787294</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="7">ИНН 7731547200</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>№ </td>
            <td>{{ $data['req']['number'] . config('custom.verification_number_request_mask') }}</td>
            <td>от </td>
            <td>{{ date('d.m.Y', strtotime($data['req']['date_from'])) }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr></tr>
        <tr>
            <td colspan="7">Просим Вас провести  первичную поверку по договору № 1 от 02.02.2019 года</td>
            <td></td>
            <td></td>
        </tr>
        <tr></tr>

        <tr>
            <td style="border: 3px solid black;">№ п/п</td>
            <td style="border: 3px solid black;" colspan="3">Наименование, модель</td>
            <td style="border: 3px solid black;" colspan="2">Диапазоны сер.№</td>
            <td style="border: 3px solid black;">Регистрационный №</td>
            <td style="border: 3px solid black;">Дата ввоза на территорию РФ</td>
            <td style="border: 3px solid black;">Кол-во</td>
        </tr>
        @foreach($data['rows'] as $num => $row)
            <tr>
                <td style="border: 1px solid black;">{{ $num + 1 }}</td>
                <td style="border: 1px solid black;" colspan="3">{{ $row['vendore_name'] }}</td>
                <td style="border: 1px solid black;">{{ $row['serial_start'] }}</td>
                <td style="border: 1px solid black;">{{ $row['serial_end'] }}</td>
                <td style="border: 1px solid black;">{{ $row['sut_number'] }}</td>
                <td style="border: 1px solid black;">{{ $row['date_import'] }}</td>
                <td style="border: 1px solid black;">{{ $row['quantity'] }}</td>
            </tr>
        @endforeach
        <tr></tr>
        <tr>
            <td></td>
            <td colspan="3">Операционный директор</td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2">А.А. Исаенков</td>
        </tr>

    </tbody>
</table>
</body>
</html>