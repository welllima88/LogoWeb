<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 16/09/14
 * Time: 11:48
 */

namespace Cib\Bundle\DataBundle\Treatment;


use Cib\Bundle\DataBundle\Entity\Transaction;
use Cib\Bundle\FtpBundle\Entity\Ftp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class Treatment {

    private $em;

    public function __construct($entityManager)
    {
        $this->em = $entityManager;
    }

    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'data';
    }


    public function downloadDataFile(Ftp $ftp)
    {
        return $ftp->downloadDataFile($this->getUploadDir());
    }

    public function treatDataFiles()
    {
        $transactions = new ArrayCollection();
        $contentDir = @scandir($this->getUploadDir());
        foreach($contentDir as $dir)
        {
            if(!in_array($dir, array('.','..')))
            {
//                var_dump($dir);
                $test = @scandir($this->getUploadDir().'/'.$dir);
//                var_dump($test);
                foreach($test as $file)
                {
                    if(!in_array($file, array('.','..')))
                    {
                        $handle = fopen($this->getUploadDir().'/'.$dir.'/'.$file,'r');
                        $content = fread($handle,filesize($this->getUploadDir().'/'.$dir.'/'.$file));
                        $raws = explode("\n",$content);
                        foreach($raws as $raw)
                        {
                            $field = explode(';',$raw);
                            $transaction = new Transaction($field[0],$field[1],$field[2],$field[3],$field[4],$field[5],$field[6],$field[7],$this->em);
                            $transactions->add($transaction);
                        }
                    }
                }
            }
        }
//        var_dump()
        return $transactions;

    }

} 