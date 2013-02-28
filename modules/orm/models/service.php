<?php

require_once( dirname(__FILE__).'/base/baseservice.php' );

/**
 * Describes a single object from livestatus
 */
class Service_Model extends BaseService_Model {
	/**
	 * A list of macros to expand for the current object
	 */
	static public $macros =  array(
		'$HOSTNAME$' => 'host.name',
		'$HOSTADDRESS$' => 'host.address',
		'$HOSTDISPLAYNAME$' => 'host.display_name',
		'$HOSTALIAS$' => 'host.alias',
		'$HOSTSTATE$' => 'host.state_text_uc',
		'$HOSTSTATEID$' => 'host.state',
		'$HOSTSTATETYPE$' => 'host.state_type_text_uc',
		'$HOSTATTEMPT$' => 'host.current_attempt',
		'$MAXHOSTATTEMPTS$' => 'host.max_check_attempts',
		'$HOSTGROUPNAME$' => 'host.first_group',
		'$SERVICEDESC$' => 'description',
		'$SERVICEDISPLAYNAME$' => 'display_name',
		'$SERVICEGROUPNAME$' => 'first_group',
		'$SERVICESTATE$' => 'state',
		'$CURRENT_USER$' => 'current_user'
	);

	/**
	 * An array containing the custom column dependencies
	 */
	static public $rewrite_columns = array(
		'state_text_uc'   => array('state_text'),
		'state_text'      => array('state','has_been_checked'),
		'first_group'     => array('groups'),
		'checks_disabled' => array('active_checks_enabled'),
		'duration'        => array('last_state_change'),
		'comments_count'  => array('comments'),
		'config_url'      => array('host.name', 'description'),
		'check_type_str'  => array('check_type')
	);

	/**
	 * Create an instance of the given type. Don't call dirctly, called from *Set_Model-objects
	 */
	public function __construct($values, $prefix) {
		parent::__construct($values, $prefix);
		$this->export[] = 'state_text';
		$this->export[] = 'checks_disabled';
		$this->export[] = 'duration';
		$this->export[] = 'comments_count';
		$this->export[] = 'config_url';
		$this->export[] = 'check_type_str';
	}

	/**
	 * Return the state as text
	 */
	public function get_state_text() {
		if( !$this->get_has_been_checked() )
			return 'pending';
		switch( $this->get_state() ) {
			case 0: return 'ok';
			case 1: return 'warning';
			case 2: return 'critical';
			case 3: return 'unknown';
		}
		return 'unknown'; // should never happen
	}

	/**
	 * Return the state type, as text in uppercase
	 */
	public function get_state_type_text_uc() {
		return $this->get_state_type()?'HARD':'SOFT';
	}

	/**
	 * Return the name of one of the services groups the object is member of
	 */
	public function get_first_group() {
		$groups = $this->get_groups();
		if(isset($groups[0])) return $groups[0];
		return '';
	}

	/**
	 * Returns true if checks are disabled
	 */
	public function get_checks_disabled() {
		//FIXME: passive as active
		return !$this->get_active_checks_enabled();
	}

	/**
	 * Returns the duration since last state change
	 */
	public function get_duration() {
		$now = time();
		$last_state_change = $this->get_last_state_change();
		if( $last_state_change == 0 )
			return -1;
		return $now - $last_state_change;
	}

	/**
	 * Return the notes url with macros expanded
	 */
	public function get_notes_url() {
		return $this->expand_macros_url(parent::get_notes_url());
	}

	/**
	 * Return the notes with macros expanded
	 */
	public function get_notes() {
		return $this->expand_macros(parent::get_notes());
	}

	/**
	 * Return the action url with macros expanded
	 */
	public function get_action_url() {
		return $this->expand_macros_url(parent::get_action_url());
	}

	/**
	 * Returns the number of comments related to the service
	 */
	public function get_comments_count() {
		return count($this->get_comments());
	}
	
	/**
	 * Get the check type as string (passive/active)
	 */
	public function get_check_type_str() {
		return $this->get_check_type() ? 'passive' : 'active';
	}
	
	/**s
	 * Get a list of custom commands for the service
	 */
	public function get_custom_commands() {
		return Custom_command_Model::parse_custom_variables($this->get_custom_variables());
	}
}
