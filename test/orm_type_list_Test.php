<?php

class ORM_Type_List_Test extends PHPUnit_Framework_TestCase {

	public function valid_values_provider () {
		return array(
			array(array(), array()),
			// must be able to handle PHP serialized data du to donwtime settings
			array('a:1:{i:0;s:1:"1";}', array("1")),
		);
	}

	/**
	 * @dataProvider valid_values_provider
	 * @group ORMType
	 */
	public function test_factory_from_array ($value, $expect) {
		$set = TestClassBPool_Model::all();
		$from_array = TestClassA_Model::factory_from_array(array("list" => $value), array());
		$this->assertSame($expect, $from_array->get_list());
	}

	/**
	 * @dataProvider valid_values_provider
	 * @group ORMType
	 */
	public function test_factory_from_setiterator ($value, $expect) {
		$set = TestClassBPool_Model::all();
		$from_iterator = TestClassA_Model::factory_from_setiterator(array("list" => $value), false, array());
		$this->assertSame($expect, $from_iterator->get_list());
	}

	/**
	 * @dataProvider valid_values_provider
	 * @group ORMType
	 */
	public function test_using_setter ($value, $expect) {
		$set_instance = TestClassA_Model::factory_from_setiterator(array(), false, array());
		$set_instance->set_list($value);
		$this->assertSame($expect, $set_instance->get_list());
	}

	/**
	 * @group ORMType
	 */
	public function test_factory_from_array_set_not_existing () {
		$from_array = TestClassA_Model::factory_from_array(array(), array());
		$this->assertSame(array(), $from_array->get_list());
	}

	/**
	 * @group ORMType
	 */
	public function test_factory_from_setiterator_set_not_existing () {
		$from_iterator = TestClassA_Model::factory_from_setiterator(array(), false, array());
		$this->assertSame(array(), $from_iterator->get_list());
	}

	/**
	 * @group ORMType
	 */
	public function test_factory_setter_set_not_existing () {
		$set_instance = TestClassA_Model::factory_from_array(array(), array());
		$set_instance->set_set(TestClassBPool_Model::none());
		$this->assertSame(array(), $set_instance->get_list());
	}

	/**
	 * The factories for the ORMTypeSet accept queries (strings) that
	 * resolve as the fields set ORM Model OR the set model.
	 */
	public function invalid_data_provider () {
		return array(
			array("foobar", "'string' is not valid for list 'list'"),
			array(true, "'boolean' is not valid for list 'list'"),
			array(1, "'integer' is not valid for list 'list'"),
			array(1.1, "'double' is not valid for list 'list'"), # double because PHP gettype is stupid
			array((object)array(), "'object' is not valid for list 'list'"),
		);
	}

	/**
	 * @dataProvider invalid_data_provider
	 * @group ORMType
	 */
	public function test_factory_from_array_invalid_values ($value, $expected) {
		$this->setExpectedException('InvalidArgumentException', $expected);
		TestClassA_Model::factory_from_array(array("list" => $value), array());
	}

	/**
	 * @dataProvider invalid_data_provider
	 * @group ORMType
	 */
	public function test_factory_from_setiterator_invalid_values ($value, $expected) {
		$this->setExpectedException('InvalidArgumentException', $expected);
		TestClassA_Model::factory_from_setiterator(array("list" => $value), false, array());
	}

	/**
	 * @dataProvider invalid_data_provider
	 * @group ORMType
	 */
	public function test_setter_invalid_values ($value, $expected) {
		$this->setExpectedException('InvalidArgumentException', $expected);
		$from_array = TestClassA_Model::factory_from_array(array(), array());
		$from_array->set_list($value);
	}

}
