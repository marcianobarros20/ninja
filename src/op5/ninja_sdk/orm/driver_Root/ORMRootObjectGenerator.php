<?php

class ORMRootObjectGenerator extends class_generator {

	private $structure;

	public function __construct() {
		$this->classname = 'BaseObject';
		$this->set_model();
	}

	public function generate($skip_generated_note = false) {
		parent::generate($skip_generated_note);
		$this->init_class();
		$this->variable( '_table', null, 'protected' );
		$this->variable( 'export', array('key'), 'protected' );
		$this->generate_export();
		$this->generate_construct();
		$this->generate_rewrite_columns();
		$this->generate_get_key();
		$this->finish_class();
	}

	private function generate_construct() {
		$this->init_function( "__construct", array( 'values', 'prefix', 'export' ) );
		$this->finish_function();
	}

	private function generate_export() {
		$this->init_function('export');
		$this->write( '$result=array();');
		$this->write( 'foreach( $this->export as $field) {' );
		$this->write(     'if(is_callable(array($this, "get_$field"))) {');
		$this->write(         '$value = $this->{"get_$field"}();');
		$this->write(         'if( $value instanceof Object'.self::$model_suffix.' ) {');
		$this->write(              '$value = $value->export();');
		$this->write(         '}');
		$this->write(         '$result[$field] = $value;');
		$this->write(     '}');
		$this->write( '}');
		$this->write( 'return $result;');
		$this->finish_function();
	}

	private function generate_rewrite_columns() {
		$this->init_function('rewrite_columns', array(), array('static', 'public'));
		$this->write('return array();');
		$this->finish_function();
	}

	/**
	 * Generate get key
	 *
	 * Should be overridden by most objects.
	 *
	 * @return void
	 **/
	private function generate_get_key() {
		$this->init_function("get_key");
		$this->write("return false;");
		$this->finish_function();
	}
}