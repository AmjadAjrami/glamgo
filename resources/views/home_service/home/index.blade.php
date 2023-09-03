@extends('home_service.layouts.app')

@section('main-content')
    <style>
        button.bs-tooltip.edit_btn,
        button.delete-btn.bs-tooltip, button.user-delete-btn.bs-tooltip{
            display: none !important;
        }
    </style>
    <div class="row match-height">
        <div class="col-xl-12 col-md-6 col-12">
            <div class="card card-statistics">
                <div class="card-body statistics-body">
                    <div class="row">
                        <div class="col-xl-4 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-primary me-2">
                                    <div class="avatar-content">
                                        <i data-feather="user" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $services_count }}</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.services')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-info me-2">
                                    <div class="avatar-content">
                                        <i data-feather="box" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $reservations_count }}</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.reservations')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6 col-12 mb-2 mb-sm-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-danger me-2">
                                    <div class="avatar-content">
                                        <i data-feather="box" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $promo_codes_count }}</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.promo_codes')</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Statistics Card -->
    </div>
    <section id="apexchart">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header d-flex flex-sm-row flex-column justify-content-md-between align-items-start justify-content-start ">
                        <div>
                            <h4 class="card-title mb-25">@lang('common.reservations')</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="line-chart_2"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        isRtl = $('html').attr('data-textdirection') === 'rtl';

        var days = @json($days);
        var reservations = @json($reservations);

        var lineChartEl = document.querySelector('#line-chart_2'),
            lineChartConfig = {
                chart: {
                    height: 400,
                    type: 'line',
                    zoom: {
                        enabled: false
                    },
                    parentHeightOffset: 0,
                    toolbar: {
                        show: false
                    }
                },
                series: [
                    {
                        data: reservations
                    }
                ],
                markers: {
                    strokeWidth: 7,
                    strokeOpacity: 1,
                    strokeColors: [window.colors.solid.warning],
                    colors: [window.colors.solid.info]
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                colors: [window.colors.solid.info],
                grid: {
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    padding: {
                        top: -20
                    }
                },
                tooltip: {
                    custom: function (data) {
                        return (
                            '<div class="px-1 py-50">' +
                            '<span>' +
                            data.series[data.seriesIndex][data.dataPointIndex] +
                            '</span>' +
                            '</div>'
                        );
                    }
                },
                xaxis: {
                    categories: days
                },
                yaxis: {
                    opposite: isRtl,
                    min: 0,
                    max: 100,
                }
            };
        if (typeof lineChartEl !== undefined && lineChartEl !== null) {
            var lineChart = new ApexCharts(lineChartEl, lineChartConfig);
            lineChart.render();
        }
    </script>
@endsection
