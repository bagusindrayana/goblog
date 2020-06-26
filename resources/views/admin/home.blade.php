@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Dashboard</div>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="row">
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-chart-area mr-1"></i>Visitor Per Day</div>
                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-chart-bar mr-1"></i>Visitor Per Month</div>
                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script>
        let dataPerDay = {!! json_encode(Helper::getDataByDay(7)) !!};
        var dataChart = {
            "label":[],
            "data":[]
        };
        $.each(dataPerDay, function(index, val) {
            dataChart['label'].push(val.tanggal);
            dataChart['data'].push(val.data);
        });
        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#292b2c';

        // Area Chart Example
        var ctx = document.getElementById("myAreaChart");
        var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dataChart['label'],
            datasets: [{
                label: "Visitor",
                lineTension: 0.3,
                backgroundColor: "rgba(2,117,216,0.2)",
                borderColor: "rgba(2,117,216,1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(2,117,216,1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(2,117,216,1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: dataChart['data'],
            }],
        }
        });


        dataPerDay = {!! json_encode(Helper::getDataByMonth(12)) !!};
        dataChart = {
            "label":[],
            "data":[]
        };
        $.each(dataPerDay, function(index, val) {
            dataChart['label'].push(val.tanggal);
            dataChart['data'].push(val.data);
        });

        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#292b2c';

        // Bar Chart Example
        var ctx = document.getElementById("myBarChart");
        var myLineChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dataChart['label'],
            datasets: [{
                label: "Visitor",
                backgroundColor: "rgba(2,117,216,1)",
                borderColor: "rgba(2,117,216,1)",
                data: dataChart['data'],
            }],
        },
        options: {
            scales: {
            xAxes: [{
                time: {
                unit: 'month'
                },
                gridLines: {
                display: false
                },
                ticks: {
                maxTicksLimit: 6
                }
            }],
            yAxes: [{
                ticks: {
                maxTicksLimit: 5
                },
                    gridLines: {
                    display: true
                }
            }],
            },
            legend: {
            display: false
            }
        }
        });
    </script>
@endpush
