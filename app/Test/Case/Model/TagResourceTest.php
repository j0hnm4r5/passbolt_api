<?php
/**
 * TagResource Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.TagResourceTest
 * @since         version 2.12.11
 * @license       http://www.passbolt.com/license
 */
App::uses('Tag', 'Model');
App::uses('Resource', 'Model');
App::uses('TagResource', 'Model');
App::uses('User', 'Model');

class TagResourceTest extends CakeTestCase {

	public $fixtures = array('app.tag', 'app.resource', 'app.tagResource', 'app.user', 'app.role');

	public function setUp() {
		parent::setUp();
		$this->TagResource = ClassRegistry::init('TagResource');
		$this->TagResource->useDb = 'test';
	}

/**
 * Test Unicity Validation
 * @return void
 */
	public function testUnicityValidation() {
		$tr = array(
			'TagResource' => array(
				'tag_id' => 'zzz00001-c5cd-11e1-a0c5-080027796c4c',
				'resource_id' => 'aaa00003-c5cd-11e1-a0c5-080027796c4c'
			)
		);
		$this->TagResource->create();
		$this->TagResource->set($tr);
		$validation = $this->TagResource->validates(array('fieldList' => array('tag_id', 'resource_id')));
		$this->assertEqual($validation, false, 'It should not be possible to associate a resource and a tag twice');
		
		$validation = $this->TagResource->uniqueCombi();
		$this->assertEqual($validation, false, 'It should not be possible to associate a resource and a tag twice');

	}

/**
 * Test TagId Validation
 * @return void
 */
	public function testTagIdValidation() {

	}

/**
 * Test ResourceId Validation
 * @return void
 */
	public function testResourceIdValidation() {

	}

/**
 * Test Tag Exist Function
 * @return void
 */
	public function testTagExist() {
		$result = $this->TagResource->tagExists(null);
		$this->assertEqual($result, false, 'Tag null should not be found');
		$result = $this->TagResource->tagExists(array('tag_id'=>'zzz00001-c5cd-11e1-a0c5-080027796c4c'));
		$this->assertEqual($result, false, 'Not existing tag should not be found');
		$result = $this->TagResource->tagExists(array('tag_id'=>'aaa00001-c5cd-11e1-a0c5-080027796c4c'));
		$this->assertEqual($result, true, 'Facebook tag should be found');
	}

/**
 * Test Resource Exist Function
 * @return void
 */
	public function testResourceExist() {
		$result = $this->TagResource->resourceExists(null);
		$this->assertEqual($result, false, 'Empty ressource should not be found');
		$result = $this->TagResource->resourceExists(array('resource_id'=>'zzz00001-c5cd-11e1-a0c5-080027796c4c'));
		$this->assertEqual($result, false, 'Not existing resource should not be found');
		$result = $this->TagResource->resourceExists(array('resource_id'=>'509bb871-5168-49d4-a676-fb098cebc04d'));
		$this->assertEqual($result, true, 'Facebook password should be found');
	}

}