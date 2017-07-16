<?php

/**
 * Класс наложения водяного знака на изображение
 * @author TUX <liketux_at_gmail_dot_com>
 */
class watermark
{
	// Ресурс исходного изображения
	private $main_img_res;
	// Ресурс водяного знака
	private $watermark_img_res;
	// Ресурс результата
	private $result_img_res;
	// Прозрачность наложения
	private $alpha_level = 100;
	private $watermark_img = './logo.png';
	// Тип выходного исображения по умолчанию
	private $result_type = 'png';

	/**
	 * Конструктор класса наложения водяных знаков
	 * @param path $image Путь до картинки
	 * @param path $watermark Путь до водяного знака
	 */
	public function  __construct($image, $watermark = null)
	{
		$func = 'imagecreatefrom' . $this->typeimage($image);
		$this->main_img_res = $func($image);
		$func = 'imagecreatefrom' . $this->typeimage($watermark);
		$this->watermark_img_res = $func($watermark ? $watermark : $this->watermark_img);
		$this->create();
	}

	/**
	 * Деструктор для очистки памяти
	 */
	public function  __destruct()
	{
		imagedestroy($this->main_img_res);
		imagedestroy($this->watermark_img_res);
		imagedestroy($this->result_img_res);
	}

	/**
	 * Функция вывода изображения с ватермарком
	 * @param string $type Необходимый тип изображения
	 * @return bool
	 */
	public function show($type = null)
	{
		$type = $type ? $type : $this->result_type;
		header("Content-type: image/" . $type);
		list($func) = $this->imagefunc($type);
		return $func($this->result_img_res);
	}

	/**
	 * Запись изображения результата на диск
	 * @param path $image Путь до файла сохранения
	 * @param int $quality Качество изображения
	 * @return bool
	 */
	public function save($image, $quality = 80)
	{
		list($func, $quality) = $this->imagefunc( pathinfo($image, PATHINFO_EXTENSION) , $quality );
		return $func($this->result_img_res, $image, $quality);
	}

	/**
	 * Записывает превью исходного изображения на диск
	 * @param path $image Путь до файла превью
	 * @param int $x Размер по x или процент если $y равен null
	 * @param int $y Размер по y
	 * @param int $quality Качество изображения
	 * @return bool
	 */
	public function thumbsource($image, $x, $y = null, $quality = 80)
	{
		list($func, $quality) = $this->imagefunc( pathinfo($image, PATHINFO_EXTENSION) , $quality );
		return $func($this->thumb($x, $y, false), $image, $quality);
	}
	/**
	 * Записывает превью изображения с водяным знаком на диск
	 * @param path $image Путь до файла превью
	 * @param int $x Размер по x или процент если $y равен null
	 * @param int $y Размер по y
	 * @param int $quality Качество изображения
	 * @return bool
	 */
	public function thumbresult($image, $x, $y = null, $quality = 80)
	{
		list($func, $quality) = $this->imagefunc( pathinfo($image, PATHINFO_EXTENSION) , $quality );
		return $func($this->thumb($x, $y, true), $image, $quality);
	}

	/**
	 * Уменьшает изображение
	 * @param int $x
	 * @param int $y
	 * @param bool $from
	 * @return resource
	 */
	private function thumb($x, $y = null, $from = true)
	{
		if($from && is_resource($this->result_img_res))
		{
			$source = $this->result_img_res;
		}
		else
		{
			$source = $this->main_img_res;
		}
		$width = imagesx($source);
		$height = imagesy($source);

		if(is_null($y))
		{
			$y = ($height / 100 ) * $x;
			$x = ($width / 100 ) * $x;
		}

		$thumb = imagecreatetruecolor($x, $y);
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $x, $y, $width, $height);
		return $thumb;
	}

	/**
	 * Определят функцию и пораметры сохранения изображения
	 * @param string $type
	 * @param int $quality
	 * @return array
	 */
	private function imagefunc($type = null, $quality = 80)
	{
		$type = strtolower($type);
		switch ($type)
		{
			case 'jpg':
			case 'jpeg':
				$result = array('imagejpeg', $quality);
				break;
			case 'png':
				$result = array('imagepng', round($quality/10-1));
				break;
			case 'gif':
				$result = array('imagegif', null);
				break;
			default:
				$result = array('imagepng', '7');
				break;
		}
		return $result;
	}

	/**
	 * Определяет тип изображения
	 * @param path $image
	 * @return string
	 */
	private function typeimage($image)
	{
		$type = strtolower(pathinfo($image, PATHINFO_EXTENSION));
		switch ($type)
		{
			case 'jpg':
			case 'jpeg':
				$result = 'jpeg';
				break;
			case 'png':
				$result ='png';
				break;
			case 'gif':
				$result = 'gif';
				break;
			default:
				$result = 'png';
				break;
		}
		return $result;
	}

	/**
	 * Накладывает водяной знак на изображение
	 * @return bool
	 */
	private function create()
	{
		$this->alpha_level /= 100;
		$main_img_res_w  = imagesx( $this->main_img_res );
		$main_img_res_h = imagesy( $this->main_img_res );
		$watermark_img_res_w = imagesx( $this->watermark_img_res );
		$watermark_img_res_h = imagesy( $this->watermark_img_res );
		$main_img_res_min_x = floor( ( $main_img_res_w / 2 ) - ( $watermark_img_res_w / 2 ) );
		$main_img_res_max_x = ceil( ( $main_img_res_w / 2 ) + ( $watermark_img_res_w / 2 ) );
		$main_img_res_min_y = floor( ( $main_img_res_h / 2 ) - ( $watermark_img_res_h / 2 ) );
		$main_img_res_max_y = ceil( ( $main_img_res_h / 2 ) + ( $watermark_img_res_h / 2 ) );
		$this->result_img_res = imagecreatetruecolor( $main_img_res_w, $main_img_res_h );
		for($y=0; $y < $main_img_res_h; $y++)
		 {
			for($x=0; $x < $main_img_res_w; $x++)
			{
				$return_color = null;
				$watermark_x = $x - $main_img_res_min_x;
				$watermark_y = $y - $main_img_res_min_y;
				$main_rgb = imagecolorsforindex( $this->main_img_res, imagecolorat( $this->main_img_res, $x, $y ) );
				if ( $watermark_x >= 0 && $watermark_x < $watermark_img_res_w && $watermark_y >= 0 && $watermark_y < $watermark_img_res_h )
				{
					$watermark_rbg = imagecolorsforindex( $this->watermark_img_res, imagecolorat( $this->watermark_img_res, $watermark_x, $watermark_y ) );
					$watermark_alpha = round( ( ( 127 - $watermark_rbg['alpha'] ) / 127 ), 2 );
					$watermark_alpha = $watermark_alpha * $this->alpha_level;
					$avg_red = $this->ave_color( $main_rgb['red'], $watermark_rbg['red'], $watermark_alpha );
					$avg_green = $this->ave_color( $main_rgb['green'], $watermark_rbg['green'], $watermark_alpha );
					$avg_blue = $this->ave_color( $main_rgb['blue'], $watermark_rbg['blue'], $watermark_alpha );
					$return_color = $this->image_color( $this->result_img_res, $avg_red, $avg_green, $avg_blue );
				}
				else
				{
					$return_color = imagecolorat( $this->main_img_res, $x, $y );
				}
				imagesetpixel( $this->result_img_res, $x, $y, $return_color );
			}
		}
		return is_resource($this->result_img_res);
	}

	/**
	 * Просчитывает средний цвет точки
	 * @param int $color_a
	 * @param int $color_b
	 * @param int $alpha_level
	 * @return int
	 */
	private function ave_color( $color_a, $color_b, $alpha_level )
	{
		return round( ( ( $color_a * ( 1 - $alpha_level ) ) + ( $color_b * $alpha_level ) ) );
	}

	/**
	 * Возвращает индекс цвета
	 * @param resource $im
	 * @param int $r
	 * @param int $g
	 * @param int $b
	 * @return int
	 */
	private function image_color($im, $r, $g, $b)
	{
		$c = imagecolorexact($im, $r, $g, $b);
		if ($c != -1)
			return $c;
		$c = imagecolorallocate($im, $r, $g, $b);
		if ($c != -1)
			return $c;
		return imagecolorclosest($im, $r, $g, $b);
	}
}

?>
