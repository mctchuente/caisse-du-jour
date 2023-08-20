@extends('layouts.app')
@section('title', __('Opérations du jour'))
@section('content')
@push('style')
<style type="text/css">
    .input-group-append {
		cursor: pointer;
	}
	.invalid-feedback {
		display: block !important;
	}
	.total-caisse-title, .operation-jour-title {
		border-bottom: solid 5px #E0D396 !important;
	}
</style>
@endpush
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header py-2">
					<div class="row">
						<div class="col-7">
							<h1 class="h3 mb-2 text-gray-800 pt-1">{{ __('Encaissements') }}</h1>
						</div>
						<div class="col-3">
							
						</div>
						<div class="col-2">
							<a href="{{route('encaissement.create')}}" class="btn btn-primary d-block align-items-center justify-content-center"><i class="fa fa-plus"></i> {{ __('Ajouter') }}</a>
						</div>
					</div>
				</div>
				
				<div class="card-body p-1">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissable" role="alert">
                            {{ session('success') }}
							@php
								Session::forget('success');
							@endphp
							<a href="#" class="close d-inline-block float-end text-white text-md-end text-decoration-none fw-bold" data-dismiss="alert" aria-label="close">×</a>
                        </div>
                    @endif

                    <div class="col-md-12 p-2 bg-white" style="min-height:55vh">
						<div class="row">
							<div class="col-md-3">
								<div class="row">
									<div class="col-md-12">
										<h4 class="total-caisse-title pt-4 pb-3">{{ __('Total caisse') }}</h4>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="d-flex align-items-center justify-content-center">
											<p class="fs-1 text-center py-5">{{ number_format($totalCaisse, 2) }} €</p>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-9">
								<div class="row">
									<div class="col-md-12">
										<h4 class="operation-jour-title pt-4 pb-3">{{ __('Opérations du jour') }}</h4>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 pt-3">
										<div class="table-responsive">
											<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
												<thead>
												<tr>
													<th>{{ __('Date') }}</th>
													<th>{{ __('Type') }}</th>
													<th>{{ __('Montant') }}</th>
													<th>{{ __('Retraits') }}</th>
													<th>{{ __('Ajouts') }}</th>
													<th>{{ __('Total') }}</th>
													<th>{{ __('Actions') }}</th>
												</tr>
												</thead>
												<tbody>
													@if (!count($encaissements))
													<tr>
														<td colspan="7">{{ __('Aucune donnée n\'est encore enregistrée dans la base de données') }}</td>
													</tr>
													@else
													@foreach($encaissements as $encaissement)
													<tr>
														<td>{{$encaissement->date_saisie}}</td>
														<td>{{$encaissement->type_operation}}</td>
														<td>{{ number_format($calculLignes['montant'][$encaissement->id], 2) }}</td>
														<td>{{ !empty($calculLignes['retrait'][$encaissement->id]) ? number_format($calculLignes['retrait'][$encaissement->id], 2) : '' }}</td>
														<td>{{ !empty($calculLignes['ajout'][$encaissement->id]) ? number_format($calculLignes['ajout'][$encaissement->id], 2) : '' }}</td>
														<td>{{ number_format($calculLignes['montant'][$encaissement->id], 2) }}</td>
														<td class="action-btn">
															<a class="btn btn-sm btn-info" href="{{ route('encaissement.edit', $encaissement) }}">
																<i class="fa fa-edit"></i>
															</a>
															&nbsp;
															<form action="{{ route('encaissement.destroy', $encaissement->id) }}" method="POST" onsubmit="return confirm('{{ __('Supprimer?') }}');" style="display: inline-block;">
																<input type="hidden" name="_method" value="DELETE">
																<input type="hidden" name="_token" value="{{ csrf_token() }}">
																<button type="submit" class="btn btn-sm btn-danger">
																	<i class="fa fa-trash"></i>
																</button>
															</form>
														</td>
													</tr>
													@endforeach
													@endif
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script>
    $(document).ready(function() {
        $(".alert-dismissible").fadeTo(2000, 500).slideUp(500, function(){
			//$(".alert-dismissible").alert('close');
			$(this).remove();
		});
        $(".close").on('click', function(e){
			e.preventDefault();
			$(".alert-dismissable").delay(1000).slideUp(200, function(){
				$(this).remove();
			});
		});
    });
</script>
@endpush
@endsection
