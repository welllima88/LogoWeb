<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 16/09/14
 * Time: 11:48
 */

namespace Cib\Bundle\DataBundle\Treatment;


use Cib\Bundle\DataBundle\Entity\Telecollecte;
use Cib\Bundle\DataBundle\Entity\Transaction;
use Cib\Bundle\FtpBundle\Entity\Ftp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class Treatment {

    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'data';
    }

    public function getTreatedDoneFile()
    {
        return 'done';
    }

    public function getTreatedFailFile()
    {
        return 'fail';
    }

    public function downloadDataFile(Ftp $ftp)
    {
        return $ftp->downloadDataFile($this->getUploadDir());
    }

    public function treatDataFiles()
    {
        $transactions = new ArrayCollection();
        $contentDir = @scandir($this->getUploadDir());

//        var_dump($contentDir);die;
        foreach($contentDir as $dir)
        {
            if(!in_array($dir, array('.','..')))
            {
                $test = @scandir($this->getUploadDir().'/'.$dir);
//                var_dump($test);
                foreach($test as $file)
                {
                    set_time_limit(30);
                    if(!in_array($file, array('.','..','Telecollectes')))
                    {
                        $tpe = $this->em->getRepository('CibActivityBundle:Tpe')->findOneBy(array('tpeNumber'=>$dir));
                        @mkdir($this->getTreatedDoneFile().'/'.$dir,0777,true);
                        @mkdir($this->getTreatedFailFile().'/'.$dir,0777,true);
                        @mkdir($this->getUploadDir().'/Telecollectes/'.$dir,0777,true);
                        $handleDone = fopen($this->getTreatedDoneFile().'/'.$dir.'/'.$file,'a');
                        $handleFail = fopen($this->getTreatedFailFile().'/'.$dir.'/'.$file,'a');
//                        var_dump($this->getUploadDir().'/'.$dir.'/'.$file);die;
                        copy($this->getUploadDir().'/'.$dir.'/'.$file,$this->getUploadDir().'/Telecollectes/'.$dir.'/'.$file);
                        $date = new \DateTime($file[12].$file[13].$file[14].$file[15].'-'.$file[16].$file[17].'-'.$file[18].$file[19]);
                        $telecollecte = new Telecollecte($date,$this->getUploadDir().'/Telecollectes/'.$dir.'/'.$file,$tpe,$tpe->getStore());
                        $this->em->persist($telecollecte);
                        $this->em->flush();
                        $handle = fopen($this->getUploadDir().'/'.$dir.'/'.$file,'r');
                        $content = fread($handle,filesize($this->getUploadDir().'/'.$dir.'/'.$file));
                        $connexion = $this->em->getConnection();
                        $configuration = $this->em->getConfiguration();
//                        var_dump($content);
                        $raws = explode("\n",$content);
                        foreach($raws as $raw)
                        {
                            if($raw != '')
                            {
                                $field = explode(';',$raw);
                                $transaction = new Transaction($field[0],$field[1],$field[2],$field[3],$field[4],$field[5],$field[6],$field[7],$field[8],$field[9],$this->em);
                            }
                            try{
                                $this->em->persist($transaction);
                                $this->em->flush();
                                fwrite($handleDone,$raw,strlen($raw));
                            }
                            catch(\Exception $e){
                                fwrite($handleFail,"TRANSACTION EN DOUBLON;".$raw,strlen($raw)+strlen("TRANSACTION EN DOUBLON;"));
                                $this->em = $this->em->create(
                                    $connexion,
                                    $configuration
                                );
                            }
                        }
                        fclose($handle);
                        fclose($handleDone);
                        fclose($handleFail);

                        if(filesize($this->getTreatedDoneFile().'/'.$dir.'/'.$file) == 0)
                            unlink($this->getTreatedDoneFile().'/'.$dir.'/'.$file);
//
                        if(filesize($this->getTreatedFailFile().'/'.$dir.'/'.$file) == 0)
                            unlink($this->getTreatedFailFile().'/'.$dir.'/'.$file);

                        unlink($this->getUploadDir().'/'.$dir.'/'.$file);
                    }
                }
            }
        }
//        die;
        return true;

    }



} 