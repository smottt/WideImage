<?php

// http://stackoverflow.com/questions/3657023/how-to-detect-shot-angle-of-photo-and-auto-rotate-for-website-display-like-desk

class WideImage_Operation_CorrectExif
{
	/**
	* Rotates and mirrors and image properly based on current orientation value
	*
	* @param WideImage_Image $img
	* @param int $orientation
	* @return WideImage_Image
	*/
	function execute($img, $orientation)
	{
		switch ($orientation)
		{
			case 2:
				return $img->mirror();
				break;

			case 3:
				return $img->rotate(180);
				break;

			case 4:
				return $img->rotate(180)->mirror();
				break;

			case 5:
				return $img->rotate(90)->mirror();
				break;

			case 6:
				return $img->rotate(90);
				break;

			case 7:
				return $img->rotate(-90)->mirror();
				break;

			case 8:
				return $img->rotate(-90);
				break;

			default: return $img->copy();
		}
	}
}