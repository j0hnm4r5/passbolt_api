<?php
/**
 * AuthenticationLog Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.AuthenticationLogTest
 * @since         version 2.13.03
 * @license       http://www.passbolt.com/license
 */
App::uses('AuthenticationLog', 'Model');

class AuthenticationTokenTest extends CakeTestCase {

	public $fixtures = array(
		'app.user',
		'app.authenticationToken'
	);

	public function setUp() {
		parent::setUp();
		$this->AuthenticationToken = ClassRegistry::init('AuthenticationToken');
		$this->User = ClassRegistry::init('User');
	}

	/**
	 * Test UserId Validation
	 * @return void
	 */
	public function testUserIdValidation() {
		$user = $this->User->findByUsername('utest@passbolt.com');
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
			'zzz00003-c5cd-11e1-a0c5-080027796c4c' => false,
			'aaa00003-c5cd-11e1-a0c5-080027796c4c' => false,
			$user['User']['id'] => true,
		);
		foreach ($testcases as $testcase => $result) {
			$authenticationToken = array('AuthenticationToken' => array('user_id' => $testcase));
			$this->AuthenticationToken->set($authenticationToken);
			if($result) $msg = 'validation of the user_id with ' . $testcase . ' should validate';
			else $msg = 'validation of the user_id with ' . $testcase . ' should not validate';
			$validate = $this->AuthenticationToken->validates(array('fieldList' => array('user_id')));
			$this->assertEqual($validate, $result, $msg);
		}
	}

	/**
	 * Test Token Validation
	 * @return void
	 */
	public function testTokenValidation() {
		$md5 = md5('test');
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'!7§5HJhYtgfgbvfdrthgfrtrgfdrtrer' => false,
			$md5 => true,
		);
		foreach ($testcases as $testcase => $result) {
			$authenticationToken = array('AuthenticationToken' => array('token' => $testcase));
			$this->AuthenticationToken->set($authenticationToken);
			if($result) $msg = 'validation of the token with ' . $testcase . ' should validate';
			else $msg = 'validation of the token with ' . $testcase . ' should not validate';
			$validate = $this->AuthenticationToken->validates(array('fieldList' => array('token')));
			$this->assertEqual($validate, $result, $msg);
		}
	}

	/**
	 * Test createToken.
	 */
	public function testCreateToken() {
		$kk = $this->User->findByUsername('kevin@passbolt.com');
		$token = $this->AuthenticationToken->createToken($kk['User']['id']);
		$this->assertEqual(!empty($token), true, 'Token should have been created, but has not');
	}

	/**
	 * Test create token for invalid user.
	 */
	public function testCreateTokenInvalidUser() {
		$token = $this->AuthenticationToken->createToken('aaa00003-c5cd-11e1-a0c5-080027z!6c4c');
		$this->assertEqual(false, $token, 'Creation of the token should have failed');
	}

	/**
	 * Test that a token is valid.
	 */
	public function testCheckTokenIsValid() {
		$kk = $this->User->findByUsername('kevin@passbolt.com');
		$token = $this->AuthenticationToken->createToken($kk['User']['id']);
		$isValid = $this->AuthenticationToken->checkTokenIsValid($token['AuthenticationToken']['token'], $kk['User']['id']);
		$this->assertEqual(is_array($isValid), true, 'The test should have returned a valid token, but has not');
	}

	/**
	 * Test that a token is valid for an invalid user
	 */
	public function testCheckTokenIsValidInvalidUser() {
		$kk = $this->User->findByUsername('kevin@passbolt.com');
		$token = $this->AuthenticationToken->createToken($kk['User']['id']);
		$isValid = $this->AuthenticationToken->checkTokenIsValid($token['AuthenticationToken']['token'], 'aaa00003-c5cd-11e1-a0c5-080027z!6c4c');
		$this->assertEqual((bool)$isValid, false, 'The test should have returned an invalid token');
	}
}