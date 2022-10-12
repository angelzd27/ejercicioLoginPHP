<?php
class pwdRandom{
    public $password;

    function newPaswword(){
        $comb = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $shfl = str_shuffle($comb);
        $this->password=substr($shfl, 0, 8);
    }
}