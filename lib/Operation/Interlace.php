<?php
   /**
	 * This file is NOT a part of WideImage.
	 * @author Matias Perrone
	 * @copyleft 2013
     * @package Internal/Operations
    **/

	/**
	 * Interlace operation class
	 *
	 * @package Internal/Operations
	 */
	class WideImage_Operation_Interlace
	{
		/**
		 * Returns a interlaced image
		 *
		 * @param WideImage_Image $img
		 * @param boolean $interlace
		 * @return WideImage_Image
		 */
		function execute($img, $interlace = true)
		{
			$new = $img->copy();
			$interlace = ($interlace ? true : false);
			if (!imageinterlace($new->getHandle(), $interlace))
					throw new WideImage_GDFunctionResultException("imageinterlace() returned false");
			return $new;
		}
	}
