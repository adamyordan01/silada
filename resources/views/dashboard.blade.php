@extends('layouts.base', ['title' => 'Dashboard'])
@section('section-header')
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item">Dashboard</div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                {{-- <div class="card-header">
                    Dashboard
                </div> --}}
                <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h5>Selamat Datang, {{ auth()->user()->name }}</h5>
                            <p style="font-size: 12pt">Anda menjabat sebagai {{ auth()->user()->position->name }}, Pada Kantor Camat Rantau Kabupaten Aceh Tamiang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success" style="background-color: #02dda5 !important">
                            {{-- <i class="fas fa-paper-plane"></i> --}}
                            <i class="fas fa-archive"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Arsip</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalArchives }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-archive"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Arsip PPAT</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalPpats }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-archive"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Arsip Non PPAT</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalNonPpat }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12 col-sm-12">
        <div class="card">
            <div class="card-header">
            <h4>Statistics</h4>
            </div>
            <div class="card-body">
            <canvas id="myChart" height="100"></canvas>
            </div>
        </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $.ajax({
            url: "{{ route('dashboard.archive-by-month') }}",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Total Arsip Per Bulan, Tahun ' + {{ date('Y') }},
                            data: data.data,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }
        })
        
    </script>
@endpush