<?php

class ExceptionE extends Exception{
    public function errorMessage() {
       
        $errorMsg = 'Error en línea '.$this->getLine().' en '.$this->getFile().': <b>'.$this->getMessage().'</b> no es válido.';
        return $errorMsg;
    }
}