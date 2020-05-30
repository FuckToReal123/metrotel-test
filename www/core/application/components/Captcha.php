<?php


namespace core\application\components;


final class Captcha
{
    /** @var int Код начального возможного символа */
    const MIN_CHAR_NUMBER = 97;
    /** @var int Код конечного возможного символа */
    const MAX_CHAR_NUMBR = 122;
    /** @var int Длинна кода капчи */
    const CODE_LENGTH = 4;
    /** @var int Ширина картинки капчи */
    const IMAGE_WIDTH = 100;
    /** @var int Высота картинки капчи */
    const IMAGE_HEIGHT = 50;

    /**
     * Создаёт код капчи
     */
    public static function generateCode()
    {
        $code = '';

        for ($i = 0; $i < self::CODE_LENGTH + 1; $i++) {
            $code .= chr(mt_rand(self::MIN_CHAR_NUMBER, self::MAX_CHAR_NUMBR));
        }

        return $code;
    }

    /**
     * Создаёт изображение капчи
     *
     * @param string $code
     * @return false|resource
     */
    public static function createImage($code)
    {
        $image = imagecreatetruecolor(self::IMAGE_WIDTH, self::IMAGE_HEIGHT);

        $font = FONTS_DIR . 'Roboto/Roboto-MediumItalic.ttf';

        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);

        imagefilledrectangle($image,0,0,self::IMAGE_WIDTH,self::IMAGE_HEIGHT,$white);
        imagettftext($image, 20, 4, 15, 32, $black, $font, $code);

        return $image;
    }
}
