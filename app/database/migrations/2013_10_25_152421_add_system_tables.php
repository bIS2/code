<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSystemTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('CREATE TABLE holdings (
		    id integer NOT NULL,
		    holdingsset_id integer NOT NULL,
		    g integer,
		    score character varying(255),
		    flag character varying(255),
		    f245b character varying(255),
		    f245c character varying(255),
		    f022a character varying(255),
		    f260a character varying(255),
		    f260b character varying(255),
		    f710a character varying(255),
		    f780t character varying(255),
		    f785t character varying(255),
		    f008x character varying(255),
		    f008y character varying(255),
		    f362a character varying(255),
		    f852h character varying(255),
		    f852b character varying(255),
		    f866a character varying(255),
		    f866z character varying(255),
		    f310a character varying(255),
		    i character(1),
		    hol_nrm character varying(255),
		    probability character varying(255),
		    is_owner character varying(5),
		    ocrr_ptrn character varying(255),
		    aux_ptrn character varying(255),
		    weight real,
		    ocrr_nr integer,
		    is_aux character varying(5),
		    j_ptrn character varying(255),
		    is_pref character varying(5)
			)'
		);

			DB::statement('CREATE TABLE holdingssets (
			    id integer NOT NULL,
			    sys1 character varying(500),
			    f245a character varying(500),
			    ptrn character varying(500)
			)'
		);

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hols');
		Schema::drop('hoss');
	}

}
