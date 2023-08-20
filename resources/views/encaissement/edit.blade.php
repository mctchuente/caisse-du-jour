@extends('layouts.app')
@section('title', __('Modifier Entrée de fond de caisse'))
@section('content')
@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style type="text/css">
    .pl-0 {
		padding-left: 0 !important;
	}
	.input-group-append {
		cursor: pointer;
	}
	.invalid-feedback {
		display: block !important;
	}
	.total-caisse-title, .operation-jour-title, .bloc-title {
		border-bottom: solid 5px #E0D396 !important;
	}
	.form-control2 {
		display: block;
		width: 100%;
		padding: 0.375rem 0.75rem;
		font-size: .9rem;
		font-weight: 400;
		line-height: 1.6;
		color: #212529;
		background-color: #f8fafc;
		background-clip: padding-box;
		border: 1px solid #dee2e6 !important;
		-webkit-appearance: none;
		-moz-appearance: none;
		appearance: none;
		border-radius: .375rem !important;
		border-top-right-radius: 0 !important;
		border-bottom-right-radius: 0 !important;
		transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out !important;
	}
	.table.table-borderless th, .table.table-borderless td {
		background-color: #ffffff !important;
	}
	.table.table-borderless tr:nth-child(1), .table.table-borderless tr:nth-child(2) {
		padding-left: 0 !important;
	}
	.table.table-borderless tr:nth-child(4) {
		text-align: right !important;
	}
</style>
@endpush
@php
$optionsOfNominalBillets = '<option value="" disabled selected>0</option>';
foreach($nominalBillets as $nominal)
if (is_array(old('nominal_type_monnaie[billets]')) && in_array($nominal, old('nominal_type_monnaie[billets]')))
$optionsOfNominalBillets .= '<option value="'.$nominal.'" selected="selected">'.$nominal.'</option>';
else
$optionsOfNominalBillets .= '<option value="'.$nominal.'">'.$nominal.'</option>';
$optionsOfNominalPieces = '<option value="" disabled selected>0</option>';
foreach($nominalPieces as $nominal)
if (is_array(old('nominal_type_monnaie[pieces]')) && in_array($nominal, old('nominal_type_monnaie[pieces]')))
$optionsOfNominalPieces .= '<option value="'.$nominal.'" selected="selected">'.$nominal.'</option>';
else
$optionsOfNominalPieces .= '<option value="'.$nominal.'">'.$nominal.'</option>';
$optionsOfNominalCentimes = '<option value="" disabled selected>0</option>';
foreach($nominalCentimes as $nominal)
if (is_array(old('nominal_type_monnaie[centimes]')) && in_array($nominal, old('nominal_type_monnaie[centimes]')))
$optionsOfNominalCentimes .= '<option value="'.$nominal.'" selected="selected">'.$nominal.'</option>';
else
$optionsOfNominalCentimes .= '<option value="'.$nominal.'">'.$nominal.'</option>';
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
				@if ($errors->any())
				<div class="alert alert-danger m-1">
					<strong>{{ __('Whoops!') }}</strong> {{ __('Il y a quelques problèmes avec les données saisies.') }}<br/><br/>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
				@endif
				
				<form method="POST" action="{{ route('encaissement.update', ['encaissement' => $encaissement]) }}" enctype="multipart/form-data">
                    @csrf
					@method('PUT')
					
					<div class="card-header">{{ __('Modifier Entrée de fond de caisse') }}</div>

					<div class="card-body p-1">
						@if (session('status'))
							<div class="alert alert-success" role="alert">
								{{ session('status') }}
							</div>
						@endif
						
						<div class="col-md-12 p-2 bg-white">
							<div class="row mb-2">
								<div class="col-md-12 px-4">
									<h4 class="bloc-title pt-4 pb-3">{{ __('Entrée de fond de caisse') }}</h4>
								</div>
							</div>
							
							<div class="row mb-1 mx-4">
								<div class="col-md-6">
									<div class="row">
										<label for="type-operation" class="col-md-12 col-form-label text-md-start">{{ __('Type d\'opération') }}</label>

										<div class="col-md-12">
											<select id="type-operation" name="type_operation" class="form-select @error('type_operation') is-invalid @enderror">
												<option value="" disabled selected>{{ __('Choisir...') }}</option>
												@if ((old('type_operation')=='dépôt de caisse')||($encaissement->type_operation == 'dépôt de caisse'))
												<option value="dépôt de caisse" selected="selected">{{ __('Dépôt de caisse') }}</option>
												@else
												<option value="dépôt de caisse">{{ __('Dépôt de caisse') }}</option>
												@endif
												@if ((old('type_operation')=='remise en banque')||($encaissement->type_operation == 'remise en banque'))
												<option value="remise en banque" selected="selected">{{ __('Remise en banque') }}</option>
												@else
												<option value="remise en banque">{{ __('Remise en banque') }}</option>
												@endif
												@if ((old('type_operation')=='retrait')||($encaissement->type_operation == 'retrait'))
												<option value="retrait" selected="selected">{{ __('Retrait') }}</option>
												@else
												<option value="retrait">{{ __('Retrait') }}</option>
												@endif
											</select>

											@error('type_operation')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-12 pt-4">
											<p class="fs-3 text-end"><span id="total-global">{{ number_format(0, 2) }}</span> €</p>
										</div>
									</div>
								</div>
							</div>
							
							<div class="row mb-2 mx-4">
								<div class="col-md-6">
									<div class="row">
										<label for="date-saisie" class="col-md-12 col-form-label text-md-start">{{ __('Date') }}</label>

										<div class="col-md-12">
											<div class="flatpickr input-group">
												<input type="text" class="form-control form-control2 @error('date_saisie') is-invalid @enderror" name="date_saisie" value="{{ !empty(old('date_saisie'))?old('date_saisie'):$encaissement->date_saisie }}" required id="date-saisie" aria-describedby="date_saisie_help" data-input />
												<span class="input-group-append">
													<a class="input-group-text bg-light d-inline-block" title="toggle" data-toggle>
														<i class="fa fa-calendar"></i>
													</a>
													<a class="input-group-text bg-light d-inline-block" style="margin-left:-4px;" title="clear" data-clear>
														<i class="fa fa-close"></i>
													</a>
												</span>
											</div>
											<span id="date_saisie_help" class="fst-italic" style="font-size:11px">DD/MM/YYYY</span>

											@error('date_saisie')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-12 pt-4">
											
										</div>
									</div>
								</div>
							</div>
							
							<div class="row mb-2 mx-4">
								<label for="commentaire" class="col-md-12 col-form-label text-md-start">{{ __('Commentaire') }}</label>

								<div class="col-md-12">
									<textarea id="commentaire" class="form-control @error('commentaire') is-invalid @enderror" name="commentaire">{{ !empty(old('commentaire'))?old('commentaire'):$encaissement->commentaire }}</textarea>

									@error('commentaire')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							
							<div class="row mb-2">
								<div class="col-md-12 px-4">
									<h4 class="bloc-title pt-4 pb-3">{{ __('Billets') }}</h4>
								</div>
							</div>
							
							<div class="row mb-2 mx-4">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-borderless" id="billetsTable" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th class="bg-white pl-0" width="2%"></th>
													<th class="bg-white pl-0" width="25%">{{ __('Nominal') }}</th>
													<th class="bg-white" width="25%">{{ __('Quantité') }}</th>
													<th class="bg-white" width="48%"></th>
												</tr>
											</thead>
											<tbody>
												@if (!count($blocSaisies['billets']))
												<tr>
													<td class="bg-white pl-0">
													</td>
													<td class="bg-white pl-0">
														<select name="nominal_type_monnaie[billets][0]" class="form-select" onchange="calculateTotalBillet()">
														<option value="" disabled selected>0</option>
														@foreach($nominalBillets as $nominal)
														@if (is_array(old('nominal_type_monnaie[billets]')) && in_array($nominal, old('nominal_type_monnaie[billets]')))
														<option value="{{$nominal}}" selected="selected">{{$nominal}}</option>
														@else
														<option value="{{$nominal}}">{{$nominal}}</option>
														@endif
														@endforeach
														</select>
													</td>
													<td class="bg-white">
														<input type="number" min="0" class="form-control txtboxNumber" name="quantite[billets][0]" value="{{ old('quantite[billets][0]') }}" onchange="calculateTotalBillet()" />
													</td>
													<td class="bg-white text-end"><span id="total-billets-0">{{ number_format(0, 2) }}</span> €</td>
												</tr>
												@else
												@foreach($blocSaisies['billets'] as $ind => $blocBillet)
												<tr>
													<td class="bg-white pl-0">
													@if ($ind > 0)
														<button type="button" class="btn btn-danger btn-sm p-1 mt-1" onclick="deleteBilletRow({{ $ind+1 }})"><span class="fa fa-trash"></span></button>
													@endif
													</td>
													<td class="bg-white pl-0">
														<select name="nominal_type_monnaie[billets][0]" class="form-select" onchange="calculateTotalBillet()">
														<option value="" disabled selected>0</option>
														@foreach($nominalBillets as $nominal)
														@if ((is_array(old('nominal_type_monnaie[billets]')) && in_array($nominal, old('nominal_type_monnaie[billets]')))||($blocBillet->nominal_type_monnaie==$nominal))
														<option value="{{$nominal}}" selected="selected">{{$nominal}}</option>
														@else
														<option value="{{$nominal}}">{{$nominal}}</option>
														@endif
														@endforeach
														</select>
													</td>
													<td class="bg-white">
														<input type="number" min="0" class="form-control txtboxNumber" name="quantite[billets][0]" value="{{ !empty(old('quantite[billets][0]'))?old('quantite[billets][0]'):$blocBillet->quantite }}" onchange="calculateTotalBillet()" />
													</td>
													<td class="bg-white text-end"><span id="total-billets-0">{{ number_format(0, 2) }}</span> €</td>
												</tr>
												@endforeach
												@endif
											</tbody>
										</table>
										<button type="button" class="btn btn-success btn-sm mx-3" onclick="appendBilletRow()">
											{{ __('Ajouter') }}
										</button>
										<input id="count-billet" type="hidden" name="count_billet" class="form-control" value="0" />
									</div>
								</div>
							</div>
							
							<div class="row mb-2">
								<div class="col-md-12 px-4">
									<h4 class="bloc-title pt-4 pb-3">{{ __('Pièces') }}</h4>
								</div>
							</div>
							
							<div class="row mb-2 mx-4">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-borderless" id="piecesTable" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th class="bg-white pl-0" width="2%"></th>
													<th class="bg-white pl-0" width="25%">{{ __('Nominal') }}</th>
													<th class="bg-white" width="25%">{{ __('Quantité') }}</th>
													<th class="bg-white" width="48%"></th>
												</tr>
											</thead>
											<tbody>
												@if (!count($blocSaisies['pieces']))
												<tr>
													<td class="bg-white pl-0">
													</td>
													<td class="bg-white pl-0">
														<select name="nominal_type_monnaie[pieces][0]" class="form-select" onchange="calculateTotalPiece()">
														<option value="" disabled selected>0</option>
														@foreach($nominalPieces as $nominal)
														@if (is_array(old('nominal_type_monnaie[pieces]')) && in_array($nominal, old('nominal_type_monnaie[pieces]')))
														<option value="{{$nominal}}" selected="selected">{{$nominal}}</option>
														@else
														<option value="{{$nominal}}">{{$nominal}}</option>
														@endif
														@endforeach
														</select>
													</td>
													<td class="bg-white">
														<input type="number" min="0" class="form-control txtboxNumber" name="quantite[pieces][0]" value="{{ old('quantite[pieces][0]') }}" onchange="calculateTotalPiece()" />
													</td>
													<td class="bg-white text-end"><span id="total-pieces-0">{{ number_format(0, 2) }}</span> €</td>
												</tr>
												@else
												@foreach($blocSaisies['pieces'] as $ind => $blocPiece)
												<tr>
													<td class="bg-white pl-0">
													@if ($ind > 0)
														<button type="button" class="btn btn-danger btn-sm p-1 mt-1" onclick="deletePieceRow({{ $ind+1 }})"><span class="fa fa-trash"></span></button>
													@endif
													</td>
													<td class="bg-white pl-0">
														<select name="nominal_type_monnaie[pieces][0]" class="form-select" onchange="calculateTotalPiece()">
														<option value="" disabled selected>0</option>
														@foreach($nominalPieces as $nominal)
														@if ((is_array(old('nominal_type_monnaie[pieces]')) && in_array($nominal, old('nominal_type_monnaie[pieces]')))||($blocPiece->nominal_type_monnaie==$nominal))
														<option value="{{$nominal}}" selected="selected">{{$nominal}}</option>
														@else
														<option value="{{$nominal}}">{{$nominal}}</option>
														@endif
														@endforeach
														</select>
													</td>
													<td class="bg-white">
														<input type="number" min="0" class="form-control txtboxNumber" name="quantite[pieces][0]" value="{{ !empty(old('quantite[pieces][0]'))?old('quantite[pieces][0]'):$blocPiece->quantite }}" onchange="calculateTotalPiece()" />
													</td>
													<td class="bg-white text-end"><span id="total-pieces-0">{{ number_format(0, 2) }}</span> €</td>
												</tr>
												@endforeach
												@endif
											</tbody>
										</table>
										<button type="button" class="btn btn-success btn-sm mx-3" onclick="appendPieceRow()">
											{{ __('Ajouter') }}
										</button>
										<input id="count-piece" type="hidden" name="count_piece" class="form-control" value="0" />
									</div>
								</div>
							</div>
							
							<div class="row mb-2">
								<div class="col-md-12 px-4">
									<h4 class="bloc-title pt-4 pb-3">{{ __('Centimes') }}</h4>
								</div>
							</div>
							
							<div class="row mb-2 mx-4">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-borderless" id="centimesTable" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th class="bg-white pl-0" width="2%"></th>
													<th class="bg-white pl-0" width="25%">{{ __('Nominal') }}</th>
													<th class="bg-white" width="25%">{{ __('Quantité') }}</th>
													<th class="bg-white" width="48%"></th>
												</tr>
											</thead>
											<tbody>
												@if (!count($blocSaisies['centimes']))
												<tr>
													<td class="bg-white pl-0">
													</td>
													<td class="bg-white pl-0">
														<select name="nominal_type_monnaie[centimes][0]" class="form-select" onchange="calculateTotalCentime()">
														<option value="" disabled selected>0</option>
														@foreach($nominalCentimes as $nominal)
														@if (is_array(old('nominal_type_monnaie[centimes]')) && in_array($nominal, old('nominal_type_monnaie[centimes]')))
														<option value="{{$nominal}}" selected="selected">{{$nominal}}</option>
														@else
														<option value="{{$nominal}}">{{$nominal}}</option>
														@endif
														@endforeach
														</select>
													</td>
													<td class="bg-white">
														<input type="number" min="0" class="form-control txtboxNumber" name="quantite[centimes][0]" value="{{ old('quantite[centimes][0]') }}" onchange="calculateTotalCentime()" />
													</td>
													<td class="bg-white text-end"><span id="total-centimes-0">{{ number_format(0, 2) }}</span> €</td>
												</tr>
												@else
												@foreach($blocSaisies['centimes'] as $ind => $blocCentime)
												<tr>
													<td class="bg-white pl-0">
													@if ($ind > 0)
														<button type="button" class="btn btn-danger btn-sm p-1 mt-1" onclick="deleteCentimeRow({{ $ind+1 }})"><span class="fa fa-trash"></span></button>
													@endif
													</td>
													<td class="bg-white pl-0">
														<select name="nominal_type_monnaie[centimes][0]" class="form-select" onchange="calculateTotalCentime()">
														<option value="" disabled selected>0</option>
														@foreach($nominalCentimes as $nominal)
														@if ((is_array(old('nominal_type_monnaie[centimes]')) && in_array($nominal, old('nominal_type_monnaie[centimes]')))||($blocCentime->nominal_type_monnaie==$nominal))
														<option value="{{$nominal}}" selected="selected">{{$nominal}}</option>
														@else
														<option value="{{$nominal}}">{{$nominal}}</option>
														@endif
														@endforeach
														</select>
													</td>
													<td class="bg-white">
														<input type="number" min="0" class="form-control txtboxNumber" name="quantite[centimes][0]" value="{{ !empty(old('quantite[centimes][0]'))?old('quantite[centimes][0]'):$blocCentime->quantite }}" onchange="calculateTotalCentime()" />
													</td>
													<td class="bg-white text-end"><span id="total-centimes-0">{{ number_format(0, 2) }}</span> €</td>
												</tr>
												@endforeach
												@endif
											</tbody>
										</table>
										<button type="button" class="btn btn-success btn-sm mx-3" onclick="appendCentimeRow()">
											{{ __('Ajouter') }}
										</button>
										<input id="count-centime" type="hidden" name="count_centime" class="form-control" value="0" />
									</div>
								</div>
							</div>
							
						</div>
						
					</div>
					
					<div class="card-footer">
						<div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-secondary">
                                    {{ __('Enregistrer') }}
                                </button>
								<a class="btn btn-default" href="{{ route('encaissement.index') }}">
									{{ __('Annuler') }}
								</a>
							</div>
                        </div>
					</div>
				</form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
@if (session()->has('locale'))
<script>
	config = {
        locale: "{{session()->get('locale')}}",
		dateFormat: "Y-m-d",
		altInput: true,
		altFormat: "d/m/Y",
		wrap: true,
		maxDate: "today",
		defaultDate: "today",
    }
    flatpickr(".flatpickr", config);
</script>
@else
<script>
	config = {
        locale: "{{config('app.locale')}}",
		dateFormat: "Y-m-d",
		altInput: true,
		altFormat: "d/m/Y",
		wrap: true,
		maxDate: "today",
		defaultDate: "today",
    }
    flatpickr(".flatpickr", config);
</script>
@endif
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script>
    $(document).ready(function() {
        $('.fa-calendar').on('click' , function(event) {
			event.preventDefault();
			$('#date-saisie').click();
			$('#date-saisie').focus();
		});
		digitFieldControl();
		calculateTotalBillet();
		calculateTotalPiece();
		calculateTotalCentime();
    });
	function appendBilletRow() {
		var billetsTable = document.getElementById("billetsTable");
		var row = billetsTable.insertRow(-1);
		var index = billetsTable.rows.length - 2;
		
		var cell = row.insertCell(-1);
		cell.innerHTML = '<button type="button" class="btn btn-danger btn-sm p-1 mt-1" onclick="deleteBilletRow(' + (index + 1) + ')"><span class="fa fa-trash"></span></button>';
		$(cell).addClass("pl-0");
		
		cell = row.insertCell(-1);
		cell.innerHTML = '<select name="nominal_type_monnaie[billets][' + index + ']" class="form-select" onchange="calculateTotalBillet()">{{!!$optionsOfNominalBillets!!}}</select>';
		$(cell).addClass("pl-0");
		
		cell = row.insertCell(-1);
		cell.innerHTML = '<input type="number" min="0" class="form-control txtboxNumber" name="quantite[billets][' + index + ']" value="{{old("quantite[billets][' + index + ']")}}" onchange="calculateTotalBillet()" />';
		
		cell = row.insertCell(-1);
		cell.innerHTML = '<span id="total-billets-' + index + '">{{ number_format(0, 2) }}</span> €';
		$(cell).addClass("text-end");
		
		digitFieldControl();
	}
	function deleteBilletRow(rowIndex) {
		if (!confirm("{{ __('Voulez-vous supprmer ?') }}")) return;
		
		var billetsTable = document.getElementById("billetsTable");
		billetsTable.deleteRow(rowIndex);
		
		var rowCount = billetsTable.rows.length;
		while (rowIndex < rowCount) {
			var row = billetsTable.rows[rowIndex];
			row.cells[0].innerHTML = '<button type="button" class="btn btn-danger btn-sm p-1 mt-1" onclick="deleteBilletRow(' + rowIndex + ')"><span class="fa fa-trash"></span></button>';
			
			var index = rowIndex - 1;
			var selectInput = row.cells[1].firstChild;
			selectInput.name = 'nominal_type_monnaie[billets][' + index + ']';
			
			var textInput = row.cells[2].firstChild;
			selectInput.name = 'quantite[billets][' + index + ']';
			
			var totalRow = row.cells[3].firstChild;
			totalRow.id = 'total-billets-' + index;
			
			++rowIndex;
		}
		
		digitFieldControl();
		calculateTotalBillet();
	}
	function calculateTotalBillet() {
		var billetsTable = document.getElementById("billetsTable");
		var rowCount = billetsTable.rows.length;
		//var totalOverall = parseFloat("0").toFixed(2);
		var totalOverall = parseFloat("0");
		var totalGlobal = document.getElementById("total-global");
		/*if (totalGlobal.innerText !== "") {
			totalOverall = parseFloat(totalGlobal.innerText);
		}*/
		var countBillet = document.getElementById("count-billet");
		var countPiece = document.getElementById("count-piece");
		var countCentime = document.getElementById("count-centime");
		for (var rowIndex = 1; rowIndex < rowCount; rowIndex++) {
			//var totalRow = parseFloat("0").toFixed(2);
			if ((rowIndex > -1)&&(rowIndex < rowCount)) {
				var row = billetsTable.rows[rowIndex];
				var selectInput = row.cells[1].firstChild;
				if (typeof selectInput.name === "undefined") {
					selectInput = selectInput.nextSibling;
				}
				var nominal = 0;
				if (selectInput.value !== "") {
					nominal = parseInt(selectInput.value);
				}
				var textInput = row.cells[2].firstChild;
				if (typeof textInput.name === "undefined") {
					textInput = textInput.nextSibling;
				}
				var quantity = 0;
				if (textInput.value !== "") {
					quantity = parseInt(textInput.value);
				}
				totalOverall += nominal * quantity;
				var totalRow = (Math.round(nominal * quantity * 100) / 100).toFixed(2);
				row.cells[3].innerHTML = '<span id="total-billets-' + rowIndex + '">' + totalRow + '</span> €';
			}
		}
		if (totalOverall === totalOverall) {//to avoid NaN
			countBillet.value = totalOverall;
			totalOverall += parseFloat(countPiece.value) + parseFloat(countCentime.value);
			totalOverall = (Math.round(totalOverall * 100) / 100).toFixed(2);
			totalGlobal.innerHTML = totalOverall;
		}
	}
	
	function appendPieceRow() {
		var piecesTable = document.getElementById("piecesTable");
		var row = piecesTable.insertRow(-1);
		var index = piecesTable.rows.length - 2;
		
		var cell = row.insertCell(-1);
		cell.innerHTML = '<button type="button" class="btn btn-danger btn-sm p-1 mt-1" onclick="deletePieceRow(' + (index + 1) + ')"><span class="fa fa-trash"></span></button>';
		$(cell).addClass("pl-0");
		
		cell = row.insertCell(-1);
		cell.innerHTML = '<select name="nominal_type_monnaie[pieces][' + index + ']" class="form-select" onchange="calculateTotalPiece()">{{!!$optionsOfNominalPieces!!}}</select>';
		$(cell).addClass("pl-0");
		
		cell = row.insertCell(-1);
		cell.innerHTML = '<input type="number" min="0" class="form-control txtboxNumber" name="quantite[pieces][' + index + ']" value="{{old("quantite[pieces][' + index + ']")}}" onchange="calculateTotalPiece()" />';
		
		cell = row.insertCell(-1);
		cell.innerHTML = '<span id="total-pieces-' + index + '">{{ number_format(0, 2) }}</span> €';
		$(cell).addClass("text-end");
		
		digitFieldControl();
	}
	function deletePieceRow(rowIndex) {
		if (!confirm("{{ __('Voulez-vous supprmer ?') }}")) return;
		
		var piecesTable = document.getElementById("piecesTable");
		piecesTable.deleteRow(rowIndex);
		
		var rowCount = billetsTable.rows.length;
		while (rowIndex < rowCount) {
			var row = piecesTable.rows[rowIndex];
			row.cells[0].innerHTML = '<button type="button" class="btn btn-danger btn-sm p-1 mt-1" onclick="deletePieceRow(' + rowIndex + ')"><span class="fa fa-trash"></span></button>';
			
			var index = rowIndex - 1;
			var selectInput = row.cells[1].firstChild;
			selectInput.name = 'nominal_type_monnaie[pieces][' + index + ']';
			
			var textInput = row.cells[2].firstChild;
			selectInput.name = 'quantite[pieces][' + index + ']';
			
			var totalRow = row.cells[3].firstChild;
			totalRow.id = 'total-pieces-' + index;
			
			++rowIndex;
		}
		
		digitFieldControl();
		calculateTotalPiece();
	}
	function calculateTotalPiece() {
		var piecesTable = document.getElementById("piecesTable");
		var rowCount = piecesTable.rows.length;
		var totalOverall = parseFloat("0");
		var totalGlobal = document.getElementById("total-global");
		/*if (totalGlobal.innerText !== "") {
			totalOverall = parseFloat(totalGlobal.innerText);
		}*/
		var countBillet = document.getElementById("count-billet");
		var countPiece = document.getElementById("count-piece");
		var countCentime = document.getElementById("count-centime");
		for (var rowIndex = 1; rowIndex < rowCount; rowIndex++) {
			if ((rowIndex > -1)&&(rowIndex < rowCount)) {
				var row = piecesTable.rows[rowIndex];
				var selectInput = row.cells[1].firstChild;
				if (typeof selectInput.name === "undefined") {
					selectInput = selectInput.nextSibling;
				}
				var nominal = 0;
				if (selectInput.value !== "") {
					nominal = parseInt(selectInput.value);
				}
				var textInput = row.cells[2].firstChild;
				if (typeof textInput.name === "undefined") {
					textInput = textInput.nextSibling;
				}
				var quantity = 0;
				if (textInput.value !== "") {
					quantity = parseInt(textInput.value);
				}
				totalOverall += nominal * quantity;
				var totalRow = (Math.round(nominal * quantity * 100) / 100).toFixed(2);
				row.cells[3].innerHTML = '<span id="total-pieces-' + rowIndex + '">' + totalRow + '</span> €';
			}
		}
		if (totalOverall === totalOverall) {//to avoid NaN
			countPiece.value = totalOverall;
			totalOverall += parseFloat(countBillet.value) + parseFloat(countCentime.value);
			totalOverall = (Math.round(totalOverall * 100) / 100).toFixed(2);
			totalGlobal.innerHTML = totalOverall;
		}
	}
	
	function appendCentimeRow() {
		var centimesTable = document.getElementById("centimesTable");
		var row = centimesTable.insertRow(-1);
		var index = centimesTable.rows.length - 2;
		
		var cell = row.insertCell(-1);
		cell.innerHTML = '<button type="button" class="btn btn-danger btn-sm p-1 mt-1" onclick="deleteCentimeRow(' + (index + 1) + ')"><span class="fa fa-trash"></span></button>';
		$(cell).addClass("pl-0");
		
		cell = row.insertCell(-1);
		cell.innerHTML = '<select name="nominal_type_monnaie[centimes][' + index + ']" class="form-select" onchange="calculateTotalCentime()">{{!!$optionsOfNominalCentimes!!}}</select>';
		$(cell).addClass("pl-0");
		
		cell = row.insertCell(-1);
		cell.innerHTML = '<input type="number" min="0" class="form-control txtboxNumber" name="quantite[centimes][' + index + ']" value="{{old("quantite[centimes][' + index + ']")}}" onchange="calculateTotalCentime()" />';
		
		cell = row.insertCell(-1);
		cell.innerHTML = '<span id="total-centimes-' + index + '">{{ number_format(0, 2) }}</span> €';
		$(cell).addClass("text-end");
		
		digitFieldControl();
	}
	function deleteCentimeRow(rowIndex) {
		if (!confirm("{{ __('Voulez-vous supprmer ?') }}")) return;
		
		var centimesTable = document.getElementById("centimesTable");
		centimesTable.deleteRow(rowIndex);
		
		var rowCount = centimesTable.rows.length;
		while (rowIndex < rowCount) {
			var row = centimesTable.rows[rowIndex];
			row.cells[0].innerHTML = '<button type="button" class="btn btn-danger btn-sm p-1 mt-1" onclick="deleteCentimeRow(' + rowIndex + ')"><span class="fa fa-trash"></span></button>';
			
			var index = rowIndex - 1;
			var selectInput = row.cells[1].firstChild;
			selectInput.name = 'nominal_type_monnaie[centimes][' + index + ']';
			
			var textInput = row.cells[2].firstChild;
			selectInput.name = 'quantite[centimes][' + index + ']';
			
			var totalRow = row.cells[3].firstChild;
			totalRow.id = 'total-centimes-' + index;
			
			++rowIndex;
		}
		
		digitFieldControl();
		calculateTotalCentime();
	}
	function calculateTotalCentime() {
		var centimesTable = document.getElementById("centimesTable");
		var rowCount = centimesTable.rows.length;
		var totalOverall = parseFloat("0");
		var totalGlobal = document.getElementById("total-global");
		/*if (totalGlobal.innerText !== "") {
			totalOverall = parseFloat(totalGlobal.innerText);
		}*/
		var countBillet = document.getElementById("count-billet");
		var countPiece = document.getElementById("count-piece");
		var countCentime = document.getElementById("count-centime");
		for (var rowIndex = 1; rowIndex < rowCount; rowIndex++) {
			if ((rowIndex > -1)&&(rowIndex < rowCount)) {
				var row = centimesTable.rows[rowIndex];
				var selectInput = row.cells[1].firstChild;
				if (typeof selectInput.name === "undefined") {
					selectInput = selectInput.nextSibling;
				}
				var nominal = 0;
				if (selectInput.value !== "") {
					nominal = parseInt(selectInput.value);
				}
				var textInput = row.cells[2].firstChild;
				if (typeof textInput.name === "undefined") {
					textInput = textInput.nextSibling;
				}
				var quantity = 0;
				if (textInput.value !== "") {
					quantity = parseInt(textInput.value);
				}
				var centimeToEuro = (nominal * quantity) / 100;//1€=100centimes
				totalOverall += centimeToEuro;
				var totalRow = (Math.round(centimeToEuro * 100) / 100).toFixed(2);
				row.cells[3].innerHTML = '<span id="total-centimes-' + rowIndex + '">' + totalRow + '</span> €';
			}
		}
		if (totalOverall === totalOverall) {//to avoid NaN
			countCentime.value = totalOverall;
			totalOverall += parseFloat(countBillet.value) + parseFloat(countPiece.value);
			totalOverall = (Math.round(totalOverall * 100) / 100).toFixed(2);
			totalGlobal.innerHTML = totalOverall;
		}
	}
	
	function digitFieldControl(){
		$(".txtboxNumber").on("keydown", function (e) {
	        // Allow: backspace, delete, tab, escape, enter and .
	        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
	             // Allow: Ctrl/cmd+A
	            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
	             // Allow: Ctrl/cmd+C
	            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
	             // Allow: Ctrl/cmd+X
	            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
	             // Allow: home, end, left, right
	            (e.keyCode >= 35 && e.keyCode <= 39)) {
	                 // let it happen, don\'t do anything
	                 return;
	        }
	        // Ensure that it is a number and stop the keypress
	        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	            e.preventDefault();
	        }
	    });
	}
</script>
@endpush
@endsection
