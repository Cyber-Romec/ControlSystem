<x-app-layout>
    <x-slot name="header">
    </x-slot>
    @push("included_scripts")
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            var arrayCodes = {!! json_encode($arrayCodes) !!};
            var arrayCourse = {!! json_encode($arrayCourse) !!};
            var array = new Array(arrayCodes.length);
            array[0] = ["Code", "Course", { role: "style" } ];
            arrayCodes.forEach((code, index) =>
            {
                array[index+1] = [code, parseFloat(arrayCourse[index]), "color: blue"];
            });

            function drawChart() {
              var data = google.visualization.arrayToDataTable(array);
        
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                            { calc: "stringify",
                                sourceColumn: 1,
                                type: "string",
                                role: "annotation" },
                            2]);
    
            var options = {
            title: "Стойност от различни валути",
            width: 600,
            height: 600,
            bar: {groupWidth: "95%"},
            legend: { position: "none" },
            };
            var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
            chart.draw(view, options);
          }
          </script>
    @endpush
    <style>
       thead th { cursor: pointer; }
    </style>
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-auto">
                <span data-href="/toCsv" id="export" class="btn btn-success" onclick="exportTasks(event.target);">Export to CSV</span>
                <span data-href="/toXls" id="export" class="btn btn-primary" onclick="exportTasks(event.target);">Export to XLS</span>
            </div>
            <div class="col-auto">
                <a class="btn btn-info mb-3" href="{{ route("currency.update")}}">update</a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <input type="text" class="form-control mb-3 tablesearch-input" data-tablesearch-table="#data-table"  placeholder="Search">
                <form action="{{ route("currency.filter") }}" method="POST">
                    @csrf
                    <label>Филтриране по курс от-до</label>
                    <div>
                        <input type="number" min="0" step=".05" value="{{ old("from") }}" placeholder="От:" name="from">
                        <input type="number" placeholder="До:" value="{{ old("to") }}" step=".05" name="to">
                        <input type="submit" value="Filter" class="btn btn-info">
                    </div>
                </form>
                <table id="data-table" class="table tablesearch-table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Валута</th>
                            <th>Код</th>
                            <th>Курс</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (isset($filteredCurrencies) ? $filteredCurrencies : $currencies as $currency)
                            <tr>
                                <td>
                                    {{$currency->currency_name}}
                                </td>
                                <td>
                                    {{$currency->code}}
                                </td>
                                <td>
                                    {{$currency->course}}
                                </td>
                            </tr>        
                        @endforeach
                    </tbody>
                </table>
                @if($currencies->links())
                    {{$currencies->links()}}
                @endif
            </div>
            <div class="col-6">
                
                 <div id="barchart_values" style="width: 900px; height: 300px"></div>
            </div>
        </div>
    </div>
    @push("scripts")
        <script src="{{asset("js/tableFilter.js")}}"></script>
        <script>
            function exportTasks(_this) {
               let _url = $(_this).data('href');
               window.location.href = _url;
            }
         </script>
        <script>
            $(document).ready(function() {

                var headers = $('#data-table thead th');
                
                $(headers[5]).attr('data-tablesort-type', 'date');

                $('table').not(".tablesort").addClass('tablesort');

            });
            
        </script>
    @endpush
</x-app-layout>
