<?php

return array(

	'already_exists'    => 'User already exists!',
	'does_not_exist'    => 'User does not exist.',
	'login_required'    => 'The login field is required',
	'password_required' => 'The password is required.',
	'password_does_not_match' => 'The passwords provided do not match.',

	'create' => array(
		'error'   => 'User was not created, please try again.',
		'success' => 'User created successfully.'
	),

    'edit' => array(
        'impossible' => 'You cannot edit yourself.',
        'error'      => 'There was an issue editing the user. Please try again.',
        'success'    => 'The user was edited successfully.'
    ),

    'delete' => array(
        'impossible' => 'You cannot delete yourself.',
        'error'      => 'There was an issue deleting the user. Please try again.',
        'success'    => 'The user was deleted successfully.'
    ),

    'alerts' => array(
        'account_created' => 'Your account has been successfully created.',
        'instructions_sent'       => 'Please check your email for the instructions on how to confirm your account.',
        'too_many_attempts' => 'Too many attempts. Try again in few minutes.',
        'wrong_credentials' => 'Incorrect username, email or password.',
        'not_confirmed' => 'Your account may not be confirmed. Check your email for the confirmation link',
        'confirmation' => 'Your account has been confirmed! You may now login.',
        'wrong_confirmation' => 'Wrong confirmation code.',
        'password_forgot' => 'The information regarding password reset was sent to your email.',
        'wrong_password_forgot' => 'User not found.',
        'password_reset' => 'Your password has been changed successfully.',
        'wrong_password_reset' => 'Invalid password. Try again',
        'wrong_token' => 'The password reset token is not valid.',
        'duplicated_credentials' => 'The credentials provided have already been used. Try with different credentials.',
    ),

);
