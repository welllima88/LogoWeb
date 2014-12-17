<?php
namespace Cib\Bundle\CustomerBundle\Opposition;

use Cib\Bundle\FtpBundle\Entity\Ftp;

/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 16/12/2014
 * Time: 17:22
 */

class Opposition
{
    private $ftp;

    public function __construct(Ftp $ftp)
    {
        $this->ftp = $ftp;

        return $this;
    }

    public function writeOppositionFile($cards)
    {
        @mkdir('oppo');

        if(file_exists('oppo/loppo.txt'))
            unlink('oppo/loppo.txt');
        $handle = fopen('oppo/loppo.txt','a');
        foreach($cards as $card)
            fwrite($handle,$card->getCardNumber()."\r\n");

        fclose($handle);

        $this->ftp->uploadOppositionFile();


    }

} 