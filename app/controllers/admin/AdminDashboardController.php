<?php

/*
*
*	Controls workflow User Dashboard. 
*
*/


class AdminDashboardController extends AdminController {

	/**
	 * Admin dashboard
	 *
	 */
	public function getIndex()
	{
        return View::make('admin/dashboard');
	}

}