@extends('Layout.index')

@section('css-body')
	<link href="{{ url('/') }}/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
	<link href="{{ url('/') }}/assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />
	<link href="{{ url('/') }}/assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />

	<link href="{{ url('/') }}/assets/css/elements/infobox.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
    /*
 * Off Canvas at medium breakpoint
 * --------------------------------------------------
 */

    @media screen and (max-width: 48em) {
      .row-offcanvas {
        position: relative;
        -webkit-transition: all 0.25s ease-out;
        -moz-transition: all 0.25s ease-out;
        transition: all 0.25s ease-out;
      }
      .row-offcanvas-left .sidebar-offcanvas {
        left: -33%;
      }
      .row-offcanvas-left.active {
        left: 33%;
        margin-left: -6px;
      }
      .sidebar-offcanvas {
        position: absolute;
        top: 0;
        width: 33%;
        height: 100%;
      }
    }
    /*
 * Off Canvas wider at sm breakpoint
 * --------------------------------------------------
 */

    @media screen and (max-width: 34em) {
      .row-offcanvas-left .sidebar-offcanvas {
        left: -45%;
      }
      .row-offcanvas-left.active {
        left: 45%;
        margin-left: -6px;
      }
      .sidebar-offcanvas {
        width: 45%;
      }
    }

    .card {
      overflow: hidden;
    }

    .card-block .rotate {
      z-index: 8;
      float: right;
      height: 100%;
    }

    .card-block .rotate i {
      color: rgba(20, 20, 20, 0.15);
      position: absolute;
      left: 0;
      left: auto;
      right: -10px;
      bottom: 0;
      display: block;
      -webkit-transform: rotate(-44deg);
      -moz-transform: rotate(-44deg);
      -o-transform: rotate(-44deg);
      -ms-transform: rotate(-44deg);
      transform: rotate(-44deg);
    }
  </style>
@endsection

@section('content-breadcumb')
	<div class="page-title">
		<h3>Dashboard</h3>
	</div>
@endsection

@section('content-body')
  <div class="row layout-top-spacing">
	
		<!-- <div class="form-row"> -->

			<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
				<a href="{{url('/order/meja/view')}}">
					<div class="widget-three">
						<div class="widget-heading">
								<h5 class="">Meja - {{$data->countMeja->total}}</h5>
						</div>
						<div class="widget-content">
							<div class="order-summary">
								<div class="summary-list">
									<div class="w-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7" y2="7"></line></svg>
									</div>
									<div class="w-summary-details">
										<div class="w-summary-info">
											<h6>Terisi</h6>
											<p class="summary-count">{{$data->countMeja->terisi}}</p>
										</div>
										<div class="w-summary-stats">
											<div class="progress">
												<div class="progress-bar bg-gradient-success" role="progressbar" style="width: {{($data->countMeja->terisi / $data->countMeja->total) * 100 }}%" aria-valuenow="{{$data->countMeja->terisi}}" aria-valuemin="0" aria-valuemax="{{$data->countMeja->total}}"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="summary-list">
									<div class="w-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
									</div>
									<div class="w-summary-details">
										<div class="w-summary-info">
											<h6>Tersedia</h6>
											<p class="summary-count">{{ $data->countMeja->kosong }}</p>
										</div>
										<div class="w-summary-stats">
											<div class="progress">
												<div class="progress-bar bg-gradient-warning" role="progressbar" style="width: {{($data->countMeja->kosong / $data->countMeja->total) * 100}}%" aria-valuenow="{{ $data->countMeja->kosong }}" aria-valuemin="0" aria-valuemax="{{ $data->countMeja->total }}"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 col-12 layout-spacing">
				<a href="{{url('/order')}}">
					<div class="widget widget-card-four">
						<div class="widget-content">
							<div class="w-content">
								<div class="w-info">
									<h6 class="value">Buat Baru</h6>
									<p class="">Transaksi</p>
								</div>
								<div class="">
									<div class="w-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
									</div>
								</div>
							</div>
							<div class="progress">
								<!-- <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 57%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div> -->
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 col-12 layout-spacing">
				<a href="{{url('/dapur')}}">
					<div class="widget widget-card-four">
						<div class="widget-content">
							<div class="w-content">
								<div class="w-info">
									<h6 class="value">Dapur</h6>
									<p class="">Daftar Pesanan</p>
								</div>
								<div class="">
									<div class="w-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
									</div>
								</div>
							</div>
							<div class="progress">
								<div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
						</div>
					</div>
				</a>
			</div>
			<!-- shift -->
			<div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 col-12 layout-spacing">
				<a href="{{url('order/index')}}">
					<div class="widget widget-card-four">
						<div class="widget-content">
							<div class="w-content">
								<div class="w-info">
									<h6 class="value">Pesanan</h6>
									<p class="">Tabel</p>
								</div>
								<div class="">
									<div class="w-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
									</div>
								</div>
							</div>
							<div class="progress">
								<div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
						</div>
					</div>
				</a>
			</div>
		@if(Perm::can(['shift_simpan']))
			<div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 col-12 layout-spacing">
				<a href="{{url($data->shift->url)}}">
					<div class="widget widget-card-four">
						<div class="widget-content">
							<div class="w-content">
								<div class="w-info">
									<h6 class="value">Shift</h6>
									<p class="">Shift Aktif:</p>
									<p>{{isset($data->shift->active) ? $data->shift->active->username . " - " . $data->shift->active->shiftstart : " - "}}</p>
								</div>
								<div class="">
									<div class="w-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
									</div>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
		@endif
		<!-- </div> -->
		@if(Perm::can(['laporan_lihat']))
			<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 layout-spacing">
				<div class="widget widget-chart-one">
						<div class="widget-heading">
							<h5 class="">Chart Penjualan Bulan {{$data->bulan}}</h5>
							<ul class="tabs tab-pills">
									<li><a href="javascript:void(0);" id="tb_1" class="tabmenu">Bulanan</a></li>
							</ul>
						</div>
						<div class="widget-content">
							<div class="tabs tab-content">
								<div id="content_1" class="tabcontent"> 
									<div id="orderBulanan"></div>
								</div>
							</div>
						</div>
				</div>
			</div>
			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 layout-spacing">
				<div class="widget widget-table-three">
					<div class="widget-heading">
						<h5 class="">Penjualan Terbanyak Bulan {{$data->bulan}}</h5>
					</div>
					<div class="widget-content">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th><div class="th-content">Menu</div></th>
										<th><div class="th-content th-heading">Harga</div></th>
										<th><div class="th-content th-heading">Total</div></th>
									</tr>
								</thead>
									<tbody>
										@foreach($data->topMenu as $menu)
											<tr>
												<td><div class="td-content product-name">{{$menu->menuname}}</div></td>
												<td><div class="td-content"><span class="pricing">{{number_format($menu->menuprice,0)}}</span></div></td>
												<td><div class="td-content">{{$menu->totalorder}}</div></td>
											</tr>
										@endforeach
									</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		@endif
		
		<!-- <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
			<div class="widget widget-activity-three">
				<div class="widget-content">
					<div class="w-info">
						<p class="">Status Shift</p>
					</div>
					<div class="mx-auto">
						<div class="timeline-line">
							<div class="item-timeline timeline-new">
								<div class="t-dot">
										<div class="t-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
								</div>
								<div class="t-content">
									<div class="t-uppercontent">
										<h5>Task Completed</h5>
										<span class=""></span>
									</div>
									<p>01 Mar, 2020</p>
								</div>
							</div>                               
						</div>                                    
					</div>
				</div>
			</div>
		</div> -->
	</div>
@endsection


@section('js-body')
	<script src="{{ url('/') }}/plugins/apex/apexcharts.min.js"></script>
	<script src="{{ url('/') }}/assets/js/dashboard/dash_2.js"></script>
	<script>
		@if(Perm::can(['laporan_lihat']))
			var sLineArea = {
					chart: {
							height: 350,
							type: 'area',
							toolbar: {
								show: false,
							}
					},
					dataLabels: {
							enabled: false
					},
					stroke: {
							curve: 'smooth'
					},
					series: [{
							name: 'Jumlah',
							data: ['{!! $data->chart->chartTotal !!}']
					}],
					xaxis: {
							categories: ['{!! $data->chart->chartTgl !!}'],                
					},
					yaxis: {
							title: {
									text: 'Rupiah'
							}
					},
					fill: {
							opacity: 1

					},
					tooltip: {
							y: {
									formatter: function (val) {
											return "Rp " + val 
									}
							}
					}
			}

			var chart = new ApexCharts(
					document.querySelector("#orderBulanan"),
					sLineArea
			);

			chart.render();
		@endif
	</script>
@endsection

