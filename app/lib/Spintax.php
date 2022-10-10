<?php 
/**
 * Spintax - A helper class to process Spintax strings.
 * @name Spintax
 * @author Jason Davis - http://www.codedevelopr.com/
 * Tutorial: http://www.codedevelopr.com/articles/php-spintax-class/
 */
class Spintax
{
    public static function process($text)
    {
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
            array('Spintax', 'replace'),
            $text
        );
    }


    public static function replace($text)
    {
        $text = Spintax::process($text[1]);
        $parts = explode('|', $text);
        return $parts[array_rand($parts)];
    }
}