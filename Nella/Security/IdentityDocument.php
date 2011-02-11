<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2011 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nellacms.com
 */

namespace Nella\Security;

/**
 * Identity document
 *
 * @author	Patrik Votoček
 * 
 * @document(repositoryClass="Nella\Models\Repository")
 * @hasLifecycleCallbacks
 * 
 * @property string $username
 * @property string $email
 * @property string $password
 * @property RoleDocument $role
 * @property string $lang
 * @property string $realname
 */
class IdentityDocument extends \Nella\Models\Document
{
	const PASSWORD_DELIMITER = "$";
	
	/**
	 * @string
	 * @index(unique=true, order="asc")
	 * @var string
	 */
	private $username;
	/**
	 * @string
	 * @var string
	 */
	private $email;
	/**
	 * @string
	 * @var string
	 */
	private $password;
	/**
	 * @referenceOne(targetDocument="RoleDocument")
	 * @var RoleDocument
	 */
	private $role;
	/**
	 * @string
	 * @var string
	 */
	private $lang;
	/**
	 * @string
	 * @var string
	 */
	private $realname;

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param string
	 * @return IdentityDocument
	 */
	public function setUsername($username)
	{
		$username = trim($username);
		$this->username = $username === "" ? NULL : $username;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param string
	 * @return IdentityDocument
	 */
	public function setEmail($email)
	{
		$email = trim($email);
		$this->email = $email == "" ? NULL : $email;
		return $this;
	}

	/**
	 * @param bool return as string
	 * @return string
	 */
	public function getPassword($string = TRUE)
	{
		if ($string || !$this->password) {
			return $this->password;
		}
		
		list($algo, $salt, $hash) = explode(self::PASSWORD_DELIMITER, $this->password);
		return array('algo' => $algo, 'salt' => $salt, 'hash' => $hash);
	}

	/**
	 * @param string
	 * @param string
	 * @return IdentityDocument
	 */
	public function setPassword($password, $algo = "sha256")
	{
		$salt = \Nette\String::random();
		$this->password = $algo . self::PASSWORD_DELIMITER . $salt . self::PASSWORD_DELIMITER . hash($algo, $salt . $password);
		return $this;
	}
	
	/**
	 * @param string plaintext password
	 * @return bool
	 */
	public function verifyPassword($password)
	{
		list($algo, $salt, $hash) = explode(self::PASSWORD_DELIMITER, $this->password);
		if (hash($algo, $salt . $password) == $hash) {
			return TRUE;
		}
		
		return FALSE;	
	}

	/**
	 * @return RoleDocument
	 */
	public function getRole()
	{
		return $this->role;
	}

	/**
	 * @param RoleDocument
	 * @return IdentityDocument
	 */
	public function setRole(RoleDocument $role)
	{
		$this->role = $role;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getLang()
	{
		return $this->lang;
	}

	/**
	 * @param string
	 * @return IdentityDocument
	 */
	public function setLang($lang)
	{
		$lang = trim($lang);
		$this->realname = $lang == "" ? NULL : $lang;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getRealname()
	{
		return $this->realname;
	}

	/**
	 * @param string
	 * @return IdentityDocument
	 */
	public function setRealname($realname)
	{
		$realname = trim($realname);
		$this->realname = $realname == "" ? NULL : $realname;
		return $this;
	}
	
	/**
	 * @prePersist
	 * @preUpdate
	 * 
	 * @throws \Nella\Models\EmptyValuesException
	 * @throws \Nella\Models\InvalidFormatException
	 * @throws \Nella\Models\DuplicateEntryException
	 */
	public function check()
	{
		parent::check();
		
		$service = $this->getModelService('Nella\Security\Models\IdentityService');
		
		if ($this->username === NULL) {
			throw new \Nella\Models\EmptyValuesException('username', "Username value must be non empty string");
		}
		if (!$service->repository->isColumnUnique($this->id, 'username', $this->username)) {
			throw new \Nella\Models\DuplicateEntryException('username', "Username value must be unique");
		}
		if ($this->email === NULL) {
			throw new \Nella\Models\EmptyValuesException('email', "Email value must be non empty string");
		}
		if (!\Nella\Tools\Validator::email($this->email)) {
			throw new \Nella\Models\InvalidFormatException('email', "Email value must be valid e-mail address");	
		}
		if ($this->lang === NULL) {
			throw new \Nella\Models\EmptyValuesException('lang', "Lang value must be non empty string");
		}
	}
}