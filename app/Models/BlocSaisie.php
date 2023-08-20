<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlocSaisie extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bloc_saisies';
    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_numeraire', 
        'nominal_type_monnaie', 
        'quantite', 
        'encaissement_id', 
    ];
	
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function getCreatedAtAttribute($value) {
		return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($value)))->format('Y-m-d H:i:s');
	}

    public function getUpdatedAtAttribute($value) {
		return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($value)))->format('Y-m-d H:i:s');
	}

    public function getDateSaisieAttribute($value) {
        if (is_null($value) || empty($value)) return $value;
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($value)))->format('Y-m-d H:i:s');
    }
	
	/**
     * The foreign keys relations between models.
     *
     */
    public function encaissement() {
        return $this->belongsTo(Encaissement::class, 'encaissement_id', 'id');
    }
}
