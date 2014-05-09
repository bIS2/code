<?php

return array(

	'already_exists'    => 'Benutzer besteht bereits!',
	'does_not_exist'    => 'Benutzer besteht noch nicht.',
	'login_required'    => 'Das Login ist obligatorisch', // pgt ??
	'password_required' => 'Das Passwort ist obligatorisch.',
	'password_does_not_match' => 'Das gegebene Passwort ist verschieden.',

	'create' => array(
		'error'   => 'Benutzer nicht erstellt. Bitte nochmals eingeben.',
		'success' => 'Benutzer erstellt.'
	),

    'edit' => array(
        'impossible' => 'Sie können nicht sich selbst editieren.',
        'error'      => 'Da war ein Problemchen beim Erstellen des Benutzers. Bitte nochmals versuchen.',
        'success'    => 'Benutzer erfolgreich editiert.'
    ),

    'delete' => array(
        'impossible' => 'Sie können nicht sich selbst löschen.',
        'error'      => 'Das war ein Problemchen beim Löschen des Benutzers. Bitte nochmals.',
        'success'    => 'Benutzer gelöscht.'
    )

    'alerts' => array(
        'account_created' => 'Ihr Konto wurde erfolgreich angelegt. Bitte prüfen Sie Ihre E-Mails um Ihr Konto zu bestätigen.',
        'too_many_attempts' => 'Zu viele Versuche. Probieren Sie es in ein paar Minuten erneut.',
        'wrong_credentials' => 'Falscher Benutzername, E-Mail oder Passwort.',
        'not_confirmed' => 'Ihr Konto wurde möglicherweise nicht bestätigt. Prüfen Sie Ihre E-Mails um Ihr Konto zu bestätigen.',
        'confirmation' => 'Ihr Konto wurde bestätigt. Sie können sich nun anmelden.',
        'wrong_confirmation' => 'Falscher Bestätigungscode.',
        'password_forgot' => 'Die Informationen zum Zurücksetzen des Passworts wurden Ihnen per E-Mail gesendet.',
        'wrong_password_forgot' => 'Benutzer nicht gefunden.',
        'password_reset' => 'Ihr Passwort wurde erfolgreich geändert.',
        'wrong_password_reset' => 'Falsches Passwort. Erneut versuchen.',
        'wrong_token' => 'Der Token zum Zurücksetzen des Passworts ist nicht valide.',
        'duplicated_credentials' => 'Ihre gewählten Kontoinformationen werden schon verwendet. Bitte versuchen Sie es mit anderen.',
    ),
);
