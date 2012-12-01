<?php

class LivestatusBasePoolClassGenerator extends class_generator {
	
	private $structure;
	private $objectclass;
	
	public function __construct( $name, $descr ) {
		$this->name = $name;
		$this->objectclass = $descr['class'];
		$this->classname = 'Base'.$descr['class'].'Pool';
		$this->set_model();
	}
	
	public function generate() {
		parent::generate();
		$this->init_class( false, array('abstract') );
		$this->generate_setbuilder_all();
		$this->finish_class();
	}
	
	private function generate_setbuilder_all() {
		$this->init_function( 'all', array(), 'static' );
		$this->write('return new LivestatusSet('
				.var_export($this->name,true)
				.','
				.var_export($this->objectclass,true)
				.');');
		$this->finish_function();
	}
}