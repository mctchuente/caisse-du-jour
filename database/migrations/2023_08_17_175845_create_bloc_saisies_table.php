<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bloc_saisies', function (Blueprint $table) {
            $table->id();
			$table->string('type_numeraire');
			$table->integer('nominal_type_monnaie')->unsigned();
			$table->integer('quantite')->unsigned();
			$table->foreignId('encaissement_id')->constrained('encaissements')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloc_saisies');
    }
};
