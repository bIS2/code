<?php

return array(
	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Das following language lines contain Das default error messages used by
	| Das validator class. Some of Dasse rules have multiple versions such
	| such as Das size rules. Feel free to tweak each of Dasse messages.
	|
	*/

	"accepted"         => "Das :attribute muss akzeptiert werden.",
	"active_url"       => "Das :attribute ist keine gültige URL.",
	"after"            => "Das :attribute muss ein Datum nach :date sein.",
	"alpha"            => "Das :attribute darf nur Buchstaben enthalten.",
	"alpha_dash"       => "Das :attribute darf nur Buchstaben, Zahlen und Bindestrick enthalten.",
	"alpha_num"        => "Das :attribute darf nur Buchstaben und Zahlen.",
	"before"           => "Das :attribute muss ein Datum vor :date sein.",
	"between"          => array(
		"numeric" => ":attribute muss zwischen :min - :max sein.",
		"file"    => ":attribute muss zwischen :min - :max Kilobytes sein.",
		"string"  => ":attribute muss zwischen :min - :max Zeichen sein.",
	),
	"confirmed"        => "Die :attribute Bestätigung stimmt nicht überein.",
	"date"             => ":attribute ist kein gültiges Datum.",
	"date_format"      => ":attribute hat anderes Format als :format.",
	"different"        => ":attribute and :other müssen unterschiedlich sein.",
	"digits"           => ":attribute müssen aus :digits Nummern bestehen.",
	"digits_between"   => ":attribute muss zwischen :min und :max Nummern bestehen.",
	"email"            => "Das :attribute Format ist ungültig.",
	"exists"           => "Das gewählte :attribute ist ungültig.",
	"image"            => "Das :attribute muss ein Bild sein.",
	"in"               => "Das gewählte :attribute ist ungültig.",
	"integer"          => "Das :attribute muss eine ganze Zahl sein.",
	"ip"               => ":attribute muss eine gültige IP Adresse sein.",
	"max"              => array(
		"numeric" => ":attribute darf nicht grösser als :max sein.",
		"file"    => ":attribute darf nicht grösser als :max kilobytes sein.",
		"string"  => ":attribute darf nicht grösser als :max characters sein.",
	),
	"mimes"            => ":attribute muss eine Datei mit dem Typ :values sein.",
	"min"              => array(
		"numeric" => "Das :attribute muss wenigstens :min betragen.",
		"file"    => "Das :attribute muss wenigstens :min kilobytes betragen.",
		"string"  => "Das :attribute muss wenigstens :min characters betragen.",
	),
	"not_in"           => "Das gewählte :attribute ist ungültig.",
	"numeric"          => "Das :attribute muss eine Nummer sein.",
	"regex"            => "Das :attribute Format ist ungültig.",
	"required"         => "Das :attribute Feld ist obligatorisch.",
	"required_if"      => "Das :attribute Feld ist obligatorisch wenn :other gleich :value ist.",
	"required_with"    => "Das :attribute Feld ist obligatorisch wenn :values vorhanden ist.",
	"required_without" => "Das :attribute Feld ist obligatorisch wenn :values nicht vorhanden ist.",
	"same"             => ":attribute und :other müssen übereinstimmen.",
	"size"             => array(
		"numeric" => "Das :attribute must be :size.",
		"file"    => "Das :attribute must be :size kilobytes.",
		"string"  => "Das :attribute must be :size characters.",
	),
	"unique"           => ":attribute ist bereits vergeben.",
	"url"              => "Das :attribute Format ist ungültig.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using Das
	| convention "attribute.rule" to name Das lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => array(),

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| Das following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => array(),

);
