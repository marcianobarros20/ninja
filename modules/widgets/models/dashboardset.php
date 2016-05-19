<?php

require_once( dirname(__FILE__).'/base/basedashboardset.php' );

/**
 * Autogenerated class DashboardSet_Model
 *
 * @todo: documentation
 */
class DashboardSet_Model extends BaseDashboardSet_Model {

	protected function get_auth_filter() {
		$auth = Auth::instance();
		$username = $auth->get_user()->get_username();

		$result_filter = new LivestatusFilterAnd();
		$result_filter->add($this->filter);
		$result_filter->add(new LivestatusFilterMatch('username', $username));
		return $result_filter;
	}
}
