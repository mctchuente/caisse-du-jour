<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encaissement extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'encaissements';
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
        'type_operation', 
        'date_saisie', 
        'commentaire', 
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
        return \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($value)))->format('Y-m-d');
    }
	
	/**
     * The foreign keys relations between models.
     *
     */
    public function blocSaisies() {
        return $this->hasMany(BlocSaisie::class, 'encaissement_id', 'id');
    }
}
