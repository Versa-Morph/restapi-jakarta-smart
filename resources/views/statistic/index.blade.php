@extends('layouts.app')

@section('breadcumbs')
@endsection

@section('content')
<section class="main--content">
    <div class="row gutter-20">
        <div class="col-12 mb-5">
            <div class="card" style="border-radius: 20px;">
                <div class="card-header p-3" style="border-radius: 20px 20px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Statistic</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group d-none">
                        <label for="dateRange">Select Date Range</label>
                        <select id="dateRange" class="form-control">
                            <option value="daily" selected>Daily</option>
                            {{-- <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option> --}}
                        </select>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-12 col-md-5">
                            <div class="form-group">
                                <label for="startDate">Start Date</label>
                                <input type="date" id="startDate" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-5">
                            <div class="form-group">
                                <label for="endDate">End Date</label>
                                <input type="date" id="endDate" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-2">
                            <button id="filterButton" class="btn btn-primary mt-2 w-100">Filter</button>
                        </div>
                    </div>

                    <canvas id="incidentChart" width="400" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment"></script>
<script>
    $(document).ready(function() {
        var chart; // Declare chart variable

        function fetchChartData(range, startDate, endDate) {
            $.ajax({
                url: 'statistic/chart-data',
                method: 'GET',
                data: { range: range, start_date: startDate, end_date: endDate },
                success: function(data) {
                    var ctx = document.getElementById('incidentChart').getContext('2d');

                    if (chart) {
                        chart.destroy(); // Destroy existing chart before creating new one
                    }

                    chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.dates,
                            datasets: [
                                {
                                    label: 'Requested',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    fill: true,
                                    tension: 0.4,
                                    data: data.requested
                                },
                                {
                                    label: 'Processed',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    fill: true,
                                    tension: 0.4,
                                    data: data.processed
                                },
                                {
                                    label: 'Completed',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    fill: true,
                                    tension: 0.4,
                                    data: data.completed
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    callbacks: {
                                        title: function(tooltipItems, data) {
                                            let title = tooltipItems[0].label;
                                            return moment(title).format('D-MMM,YYYY');
                                        }
                                    }
                                },
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        font: {
                                            size: 14
                                        },
                                        usePointStyle: true
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    type: 'time',
                                    time: {
                                        unit: range === 'daily' ? 'day' : range === 'monthly' ? 'month' : 'year'
                                    },
                                    title: {
                                        display: true,
                                        text: `Date`,
                                        font: {
                                            size: 16
                                        }
                                    },
                                    ticks: {
                                        maxRotation: 0,
                                        autoSkip: true,
                                        callback: function(value, index, values) {
                                            return moment(value).format('D');
                                        }
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Count',
                                        font: {
                                            size: 16
                                        }
                                    },
                                    suggestedMin: 0,
                                    suggestedMax: 30,
                                    ticks: {
                                        stepSize: 5,
                                    }
                                }
                            }
                        }
                    });
                },
                error: function() {
                    console.error('An error occurred while fetching the chart data.');
                }
            });
        }

        // Set default date range to last 30 days
        var endDate = moment().format('YYYY-MM-DD');
        var startDate = moment().subtract(30, 'days').format('YYYY-MM-DD');

        $('#startDate').val(startDate);
        $('#endDate').val(endDate);

        // Fetch initial data
        fetchChartData('daily', startDate, endDate);

        // Update chart data when the filter button is clicked
        $('#filterButton').click(function() {
            var range = $('#dateRange').val();
            startDate = $('#startDate').val();
            endDate = $('#endDate').val();
            fetchChartData(range, startDate, endDate);
        });
    });
</script>
@endpush
