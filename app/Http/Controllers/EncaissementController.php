<?php

namespace App\Http\Controllers;

use App\Models\Encaissement;
use Illuminate\Http\Request;
use App\Models\BlocSaisie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Database\Eloquent\Builder;

class EncaissementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$encaissements = Encaissement::all();
        $encaissements = Encaissement::orderBy('id', 'desc')->get()->all();
		$totalCaisse = 0;
		$calculLignes = array('montant' => array(), 'retrait' => array(), 'ajout' => array());
		foreach ($encaissements as $encaissement) {
			$montantRow = 0;
			foreach($encaissement->blocSaisies as $bloc) {
				if ($bloc->type_numeraire == 'centimes')
					$montantRow += (floatval($bloc->nominal_type_monnaie) * floatval($bloc->quantite)) / 100;
				else
					$montantRow += floatval($bloc->nominal_type_monnaie) * floatval($bloc->quantite);
			}
			if ($encaissement->type_operation == 'dépôt de caisse') {
				$totalCaisse += $montantRow;
				$calculLignes['ajout'][$encaissement->id] = $montantRow;
			} else {
				$totalCaisse -= $montantRow;
				$calculLignes['retrait'][$encaissement->id] = $montantRow;
			}
			$calculLignes['montant'][$encaissement->id] = $montantRow;
		}
		return view('encaissement.index', compact('encaissements', 'calculLignes', 'totalCaisse'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nominalBillets = [5, 10, 20, 50, 100, 200];
        $nominalPieces = [1, 2];
		$nominalCentimes = [1, 2, 5, 10, 20, 50];
        return view('encaissement.create', compact('nominalBillets', 'nominalPieces', 'nominalCentimes'));
    }

    /**
     * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {
        $withErrors = array();
		try {
			// Validator rules definition
			$rules = array(
				'type_operation'   		=> 'required|string',
				'date_saisie'   		=> 'required|string',
				'nominal_type_monnaie'  => 'required|array',
				'quantite'  			=> 'required|array',
			);
			$requestData = $request->all();
			$validator = Validator::make($requestData, $rules);
			if ($validator->fails()) {
				// redirect with errors
				return redirect()
					->route('encaissement.create')
					->withErrors($validator)
					->withInput();
			} else {
				if (empty($requestData['nominal_type_monnaie']['billets']) || empty($requestData['quantite']['billets']) || empty($requestData['quantite']['billets'][0])
					|| empty($requestData['nominal_type_monnaie']['pieces']) || empty($requestData['quantite']['pieces']) || empty($requestData['quantite']['pieces'][0])
					|| empty($requestData['nominal_type_monnaie']['centimes']) || empty($requestData['quantite']['centimes']) || empty($requestData['quantite']['centimes'][0])) {
					$withErrors[] = __('Please provide datas');
				} else {
					if ($request->has('_token')) {
						unset($requestData['_token']);
					}
					// Create the Encaissement
					$encaissement = Encaissement::create($requestData);
					if (!is_null($encaissement)) {
						//save encaissement bloc saisie
						$blocData = array();
						$blocData['encaissement_id'] = $encaissement->id;
						foreach($requestData['nominal_type_monnaie'] as $key => $data) {
							$blocData['type_numeraire'] = $key;
							foreach($data as $ind => $value) {
								$blocData['nominal_type_monnaie'] = $value;
								if (!empty($requestData['quantite'][$key][$ind])) {
									$blocData['quantite'] = $requestData['quantite'][$key][$ind];
									$blocSaisie = BlocSaisie::create($blocData);
								}
							}
						}
						// redirect
						return redirect()->route('encaissement.index')->with('success', __('Successfully Added!'));
					} else {
						$withErrors[] = __('Something went wrong');
					}
				}
			}
		} catch (\Illuminate\Testing\Exceptions\InvalidArgumentException $exception) {
			// You can check get the details of the error using `errorInfo`:
			$withErrors[] = $exception->errorInfo;
		} catch (\Illuminate\Database\QueryException $exception) {
			// You can check get the details of the error using `errorInfo`:
			$withErrors[] = $exception->errorInfo;
		}
		if (!empty($withErrors) && (sizeof($withErrors) > 0)) {
            // redirect with errors
			return redirect()
                    ->route('encaissement.create')
                    ->withErrors($withErrors)
                    ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Encaissement $encaissement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
	 *
     * @param  \App\Models\Encaissement $encaissement
     */
    public function edit(int $id)
    {
        if (empty($encaissement) || empty($encaissement->attributes)) {
            $encaissement = Encaissement::find($id);
        }
		$nominalBillets = [5, 10, 20, 50, 100, 200];
        $nominalPieces = [1, 2];
		$nominalCentimes = [1, 2, 5, 10, 20, 50];
		$blocSaisies = array('billets' => array(), 'pieces' => array(), 'centimes' => array());
		$blocData = $encaissement->blocSaisies;
		if (empty($blocData)) {
			$blocSaisies['billets'][] = null;
			$blocSaisies['pieces'][] = null;
			$blocSaisies['centimes'][] = null;
		} else foreach ($blocData as $key => $blocSaisie) {
			$blocSaisies[$blocSaisie->type_numeraire][] = $blocSaisie;
		}
		return view('encaissement.edit', compact('encaissement', 'blocSaisies', 'nominalBillets', 'nominalPieces', 'nominalCentimes'));
    }

    /**
     * Update the specified resource in storage.
	 *
     * @param  \App\Models\Encaissement $encaissement
     */
    public function update(Request $request, int $id)
    {
        $encaissement = Encaissement::find($id);
        if (is_null($encaissement) || empty($encaissement->id)) {
            return redirect()->route('encaissement.index')->with('message_error', __('404 ResourcePerson not Found!'));
        }
		$withErrors = array();
		try {
			// Validator rules definition
			$rules = array(
				'type_operation'   		=> 'required|string',
				'date_saisie'   		=> 'required|string',
				'nominal_type_monnaie'  => 'required|array',
				'quantite'  			=> 'required|array',
			);
			$requestData = $request->all();
			$validator = Validator::make($requestData, $rules);
			if ($validator->fails()) {
				// redirect with errors
				return redirect('encaissement/'.$encaissement->id.'/edit')
					->withErrors($validator)
					->withInput();
			} else {
				if (empty($requestData['nominal_type_monnaie']['billets']) || empty($requestData['quantite']['billets']) || empty($requestData['quantite']['billets'][0])
					|| empty($requestData['nominal_type_monnaie']['pieces']) || empty($requestData['quantite']['pieces']) || empty($requestData['quantite']['pieces'][0])
					|| empty($requestData['nominal_type_monnaie']['centimes']) || empty($requestData['quantite']['centimes']) || empty($requestData['quantite']['centimes'][0])) {
					$withErrors[] = __('Please provide datas');
				} else {
					if ($request->has('_token')) {
						unset($requestData['_token']);
					}
					if ($request->has('_method')) {
						unset($requestData['_method']);
					}
					// Update the Encaissement
					$encaissement->fill($requestData)->save();
					$blocData = $encaissement->blocSaisies;
					if (!empty($blocData)) foreach ($blocData as $key => $blocSaisie) {
						$blocSaisie->delete();
					}
					//save encaissement bloc saisie
					$blocData = array();
					$blocData['encaissement_id'] = $encaissement->id;
					foreach($requestData['nominal_type_monnaie'] as $key => $data) {
						$blocData['type_numeraire'] = $key;
						foreach($data as $ind => $value) {
							$blocData['nominal_type_monnaie'] = $value;
							if (!empty($requestData['quantite'][$key][$ind])) {
								$blocData['quantite'] = $requestData['quantite'][$key][$ind];
								$blocSaisie = BlocSaisie::create($blocData);
							}
						}
					}
					// redirect
					return redirect()->route('encaissement.index')->with('success', __('Successfully Updated!'));
				}
			}
		} catch (\Illuminate\Testing\Exceptions\InvalidArgumentException $exception) {
			// You can check get the details of the error using `errorInfo`:
			$withErrors[] = $exception->errorInfo;
		} catch (\Illuminate\Database\QueryException $exception) {
			// You can check get the details of the error using `errorInfo`:
			$withErrors[] = $exception->errorInfo;
		}
		if (!empty($withErrors) && (sizeof($withErrors) > 0)) {
			// redirect with errors
            return redirect('encaissement/'.$encaissement->id.'/edit')
                    ->withErrors($withErrors)
                    ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Encaissement $encaissement)
    {
        //$encaissement = Encaissement::find($id);
        if (empty($encaissement) || empty($encaissement->id)) {
            return redirect()->route('encaissement.index')->with('message_error', __('404 ResourcePerson not Found!'));
        }
		$encaissement->delete();
        return back()->with('success', __('Successfully Deleted!'));
    }
}
