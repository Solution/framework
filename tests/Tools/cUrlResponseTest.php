<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2011 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nella-project.org
 */

namespace NellaTests\Tools;

require_once __DIR__ . "/../bootstrap.php";

class cUrlResponseTest extends \PHPUnit_Framework_TestCase
{
	/** @var Nella\Tools\cUrlResponse */
	private $response;
	
	public function setUp()
	{
		$req = new \Nella\Tools\cUrlRequest("http://www.google.com");
		$this->response = $req->getResponse();
	}
	
	public function testRequest()
	{
		$this->assertInstanceOf('Nella\Tools\cUrlRequest', $this->response->getRequest(), "->getRequest()");
		$this->assertInstanceOf('Nella\Tools\cUrlRequest', $this->response->request, "->request");
	}
	
	public function testHeaders()
	{
		// Headers
		$this->assertTrue(is_array($this->response->getHeaders()), "->getHeaders()");
		$this->assertTrue(is_array($this->response->headers), "->headers");
		$this->assertEquals(200, $this->response->headers['Status-Code'], "->headers[]");
		
		// Header
		$this->assertEquals(200, $this->response->getHeader('Status-Code'), "->getHeader()");
	}
	
	public function testBody()
	{
		$this->assertStringStartsWith("<!doctype html>", $this->response->getBody(), "->getBody()");
		$this->assertStringStartsWith("<!doctype html>", $this->response->body, "->body");
		$this->assertStringStartsWith("<!doctype html>", (string) $this->response, "->__toString()");
	}
}