@extends('admin.layouts.app')

@section('main-content')
    <style>
        .apexcharts-legend.apexcharts-align-center.position-bottom{
            display: none;
        }
    </style>
    <div class="row match-height">
        <div class="col-xl-12 col-md-6 col-12">
            <div class="card card-statistics">
                <div class="card-body statistics-body">
                    <div class="row">
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-primary me-2">
                                    <div class="avatar-content">
                                        <i data-feather="user" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $users_count }}</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.users')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-info me-2">
                                    <div class="avatar-content">
                                        <i data-feather="box" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $men_salons_count }}</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.men_salons')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-info me-2">
                                    <div class="avatar-content">
                                        <i data-feather="box" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $women_salons_count }}</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.women_salons')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-danger me-2">
                                    <div class="avatar-content">
                                        <i data-feather="user" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $artists_count }}</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.artists')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0 mt-3">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-primary me-2">
                                    <div class="avatar-content">
                                        <i data-feather="box" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $cities_count }}</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.cities')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0 mt-3">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-warning me-2">
                                    <div class="avatar-content">
                                        <i data-feather="box" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $offers_count }}</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.offers')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0 mt-3">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-warning me-2">
                                    <div class="avatar-content">
                                        <i data-feather="box" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $products_count }}</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.products')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0 mt-3">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-warning me-2">
                                    <div class="avatar-content">
                                        <i data-feather="box" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $orders_count }}</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.orders')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0 mt-3">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-warning me-2">
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

            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header d-flex flex-sm-row flex-column justify-content-md-between align-items-start justify-content-start ">
                        <div>
                            <h4 class="card-title mb-25">@lang('common.users')</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="line-chart_3"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-12">
                <div class="card">
                    <div class="card-header flex-column align-items-start">
                        <h4 class="card-title mb-75">@lang('common.users')</h4>
                        <span class="card-subtitle text-muted">@lang('common.users_cities')</span>
                    </div>
                    <div class="card-body">
                        <div id="donut-chart_2"></div>
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

        var users_chart_count = @json($users_chart_count);

        var lineChartEl2 = document.querySelector('#line-chart_3'),
            lineChartConfig2 = {
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
                        data: users_chart_count
                    }
                ],
                markers: {
                    strokeWidth: 7,
                    strokeOpacity: 1,
                    strokeColors: [window.colors.solid.dark],
                    colors: [window.colors.solid.danger]
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                colors: [window.colors.solid.danger],
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
        if (typeof lineChartEl2 !== undefined && lineChartEl2 !== null) {
            var lineChart2 = new ApexCharts(lineChartEl2, lineChartConfig2);
            lineChart2.render();
        }

        var cities = @json($cities);
        var users = @json($users);
        var cities_colors = @json($colors);

        var donutChartEl = document.querySelector('#donut-chart_2'),
            donutChartConfig = {
                chart: {
                    height: 350,
                    type: 'donut'
                },
                legend: {
                    show: true,
                    position: 'bottom'
                },
                labels: cities,
                series: users,
                colors: cities_colors,
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opt) {
                        return parseInt(val) + '%';
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                name: {
                                    fontSize: '2rem',
                                    fontFamily: 'Montserrat'
                                },
                                value: {
                                    fontSize: '1rem',
                                    fontFamily: 'Montserrat',
                                    formatter: function (val) {
                                        return parseInt(val);
                                    }
                                },
                                total: {
                                    show: true,
                                    fontSize: '1.5rem',
                                    label: '@lang('common.city')',
                                    formatter: function (w) {
                                        return '0';
                                    }
                                }
                            }
                        }
                    }
                },
                responsive: [
                    {
                        breakpoint: 992,
                        options: {
                            chart: {
                                height: 380
                            }
                        }
                    },
                    {
                        breakpoint: 576,
                        options: {
                            chart: {
                                height: 320
                            },
                            plotOptions: {
                                pie: {
                                    donut: {
                                        labels: {
                                            show: false,
                                            name: {
                                                fontSize: '1.5rem'
                                            },
                                            value: {
                                                fontSize: '1rem'
                                            },
                                            total: {
                                                fontSize: '1.5rem'
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                ]
            };
        if (typeof donutChartEl !== undefined && donutChartEl !== null) {
            var donutChart = new ApexCharts(donutChartEl, donutChartConfig);
            donutChart.render();
        }
    </script>
@endsection
