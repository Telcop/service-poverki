<div>
    <h4>Ошибки импорта:</h3>
    @isset($data['model'])
        @foreach ($data['model'] as $item)
            <div><strong>Строка {{ $item['num'] }}</strong>: модель <strong>{{ $item['model'] }}</strong> не заведена в справочнике.</div>
        @endforeach
    @endisset
    @isset($data['unique'])
        @foreach ($data['unique'] as $item)
            <div><strong>Строка {{ $item['num'] }}</strong>. Инвойс <strong>№{{ $item['inv_no'] }}</strong>, модель <strong>{{ $item['model'] }}</strong> с серийными номерами с <strong>{{ $item['serial_start'] }}</strong> по <strong>{{ $item['serial_end'] }}</strong> уже существуют c ID = <strong>{{ $item['id_exist'] }}</strong>.</div>
        @endforeach
    @endisset
</div>
