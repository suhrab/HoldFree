<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Gee
 * Date: 7/2/13
 * Time: 6:25 PM
 * To change this template use File | Settings | File Templates.
 */

class ExceptionImproved extends Exception
{
    public function __construct($message, $title = 'Ошибка', $code = 0)
    {
        parent::__construct($message, $code);

        $this->title = $title;
    }

    public function getTitle(){
        return $this->title;
    }

    private $title = '';
}