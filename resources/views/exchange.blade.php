<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <table class="table table-striped">
        <tr>
            <th>Валута</th>
            <th>Код</th>
            <th>Курс</th>
        </tr>
        @foreach ($currencies as $currency => $kode)
            <tr>
                <td>
                    {{$kode}}
                </td>
                <td>
                    {{$currency}}
                </td>
                <td>
                    @php
                        try {
                            echo $rates["rates"][$currency];
                        } catch (\Throwable $th) {
                            echo 'null';
                        }
                    @endphp
                </td>
            </tr>
            
        @endforeach
    </table>

</x-app-layout>