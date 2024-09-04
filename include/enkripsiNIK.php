<?php
function enkripsi($nik)
{
    $a=substr($nik,0,1);
    $b=substr($nik,1,1);
    $c=substr($nik,2,1);
    $d=substr($nik,3,1);
    $e=substr($nik,4,1);
    $f=substr($nik,5,1);
    
//    enkripsi kode
    $x1='2a1d2';
    $x2='f297a';
    $x3='57a5a';
    $x4='a0e4a';
    $x5='801fc';
    $x6='b0140';
    $balikan=$x1.$a.$x2.$b.$x3.$c.$x4.$d.$x5.$e.$x6.$f;
    
    return $balikan;
}

function dekripsi($nikencrypted)
{
    $a=substr($nikencrypted,5,1);
    $b=substr($nikencrypted,11,1);
    $c=substr($nikencrypted,17,1);
    $d=substr($nikencrypted,23,1);
    $e=substr($nikencrypted,29,1);
    $f=substr($nikencrypted,35,1);
    
    return $a.$b.$c.$d.$e.$f;
}
