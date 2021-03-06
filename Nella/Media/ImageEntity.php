<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2011 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nella-project.org
 */

namespace Nella\Media;

/**
 * Image media entity
 * 
 * @entity(repositoryClass="Nella\Models\Repository")
 * @table(name="media_images")
 * 
 * @author	Patrik Votoček
 */
class ImageEntity extends BaseFileEntity implements IImage
{
	/**
	 * @return \Nella\Image
	 */
	public function toImage()
	{
		return \Nella\Image::fromFile(STORAGE_DIR . "/" . $this->getPath());
	}
	
	/**
	 * @return string
	 */
	public function getType()
	{
		$types = array(
			'image/jpg' => "jpg", 
			'image/jpeg' => "jpg", 
			'image/png' => "png", 
			'image/gif' => "gif", 
		);
		
		$mime = $this->getMimeType();
		return isset($types[$mime]) ? $types[$mime] : "jpg";
	}
}
