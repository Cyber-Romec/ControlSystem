<x-app-layout>
    <x-slot name="header">
    </x-slot>
    @push("included_scripts")
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <style>
       thead th { cursor: pointer; }
    </style>
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
                console.log(array.length * 10);
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                            { calc: "stringify",
                                sourceColumn: 1,
                                type: "string",
                                role: "annotation" },
                            2]);
    
            var options = {
            title: "Стойност от различни валути",
            width: 400,
            height: array.length * 55.5,
            bar: {groupWidth: "95%"},
            legend: { position: "none" },
            };
            var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
            chart.draw(view, options);
          }
          </script>
    
    
    
    
    @endpush
    
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <input type="text" class="form-control mb-3 tablesearch-input" data-tablesearch-table="#data-table"  placeholder="Търси по Валута, Код и Курс">
                <form action="{{ route("currency.filter") }}" class="mb-3" method="GET">
                    @csrf
                    <label>Филтриране по курс от-до:</label>
                    <br>
                    <span class="blockquote-footer">Филтрирането показва резултат от всички записи в базата данни!</span>
                    <div>
                        <input type="number" min="0" step=".05" value="{{ $from ?? "" }}" placeholder="От:" name="from">
                        <input type="number" placeholder="До:" value="{{ $to ?? "" }}" step=".05" name="to">
                        <input type="submit" value="Filter" class="btn btn-info">
                    </div>
                </form>
                @if(request()->routeIs('currency.filter'))
                    <a href="{{ route("currency.index") }}" class="btn btn-warning" >Reset</a>
                @endif
                <table id="data-table" class="table tablesearch-table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Валута</th>
                            <th>Код</th>
                            <th>Курс</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($currencies as $currency)
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
                        @empty       
                            None
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="barchart_values" class="col-md-6" >
                 
            </div>
                
        </div>
        @if(request()->routeIs("currency.index"))
            {{$currencies->links()}}   
        @endif
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
