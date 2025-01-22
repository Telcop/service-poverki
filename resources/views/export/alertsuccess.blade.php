<div>
    <div>Создана заявка №{{ $data['num'] . config('custom.verification_number_request_mask') }} от {{ $date }}</div>
    <div><a href="{{ route('downloadfile', $data) }}">Скачать файл поверки</a></div>
</div>
