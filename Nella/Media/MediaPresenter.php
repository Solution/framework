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
 * Media presenter
 * 
 * @author	Patrik Votoček
 */
class MediaPresenter extends \Nella\Application\UI\Presenter
{
	/**
	 * @param IFile
	 */
	public function actionFile(IFile $file)
	{
		$this->sendResponse(new \Nette\Application\Responses\FileResponse(
			$file->getContent(), 
			$file->getFilename(), 
			$file->getMimeType()
		));
		$this->terminate();
	}
	
	/**
	 * @param IImage
	 * @param IFormat
	 * @param string
	 * @param int	 	 
	 */
	public function actionImage(IImage $image, IFormat $format, $path, $type = NULL)
	{
		if ($format) {
			$image = $format->process($image);
		} else {
			$image = $image->toImage();
		}
		
		$dir = pathinfo(WWW_DIR . $path, PATHINFO_DIRNAME);
		if (!file_exists($dir)) {
			mkdir($dir, 0777, TRUE);
		}
		
		$image->save(WWW_DIR . $path);
		if (!$type) {
			$image->send();
		} else {
			$image->send($type);
		}
		
		$this->terminate();
	}
}
