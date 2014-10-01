<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 03/09/14
 * Time: 15:00
 */

namespace Cib\Bundle\FtpBundle\Entity;


use Cib\Bundle\ActivityBundle\Entity\Tpe;

class Ftp {

    private $ftpHost;

    private $ftpLogin;

    private $ftpPassword;

    private $ftpPort;

    private $ftpIsSecured;

    private $ftpMode;

    private $ftpHandle;

    private $ftpError;



    /////////////// GETTER / SETTER

    public function setFtpHost($ftpHost)
    {
        $this->ftpHost = $ftpHost;
    }

    public function getFtpHost()
    {
        return $this->ftpHost;
    }

    public function setFtpLogin($ftpLogin)
    {
        $this->ftpLogin = $ftpLogin;
    }

    public function getFtpLogin()
    {
        return $this->ftpLogin;
    }

    public function setFtpPassword($ftpPassword)
    {
        $this->ftpPassword = $ftpPassword;
    }

    public function getFtpPassword()
    {
        return $this->ftpPassword;
    }

    public function setFtpPort($ftpPort)
    {
        $this->ftpPort = $ftpPort;
    }

    public function getFtpPort()
    {
        return $this->ftpPort;
    }

    public function setFtpIsSecured($ftpIsSecured)
    {
        $this->ftpIsSecured = $ftpIsSecured;
    }

    public function getFtpIsSecured()
    {
        return $this->ftpIsSecured;
    }

    public function setFtpMode($ftpMode)
    {
        $this->ftpMode = $ftpMode;
    }

    public function getFtpMode()
    {
        return $this->ftpMode;
    }

    public function setFtpHandle($ftpHandle)
    {
        $this->ftpHandle = $ftpHandle;
    }

    public function getFtpHandle()
    {
        return $this->ftpHandle;
    }

    public function setFtpError($ftpError)
    {
        return $this->ftpError = $ftpError;
    }

    public function getFtpError()
    {
        return $this->ftpError;
    }


    public function __construct($ftpHost, $ftpLogin, $ftpPassword, $ftpPort, $ftpMode, $ftpIsSecured)
    {
        $this->ftpHost = $ftpHost;
        $this->ftpLogin = $ftpLogin;
        $this->ftpPassword = $ftpPassword;
        $this->ftpPort = $ftpPort;
        $this->ftpMode = $ftpMode;
        $this->ftpIsSecured = $ftpIsSecured;
    }

    public function connect()
    {
        if($this->ftpIsSecured === false)
        {
            if(($this->ftpHandle = ftp_connect($this->ftpHost, $this->ftpPort)) != false)
            {
                if(@ftp_login($this->ftpHandle,$this->ftpLogin,$this->ftpPassword) != false)
                {
                    ftp_pasv($this->ftpHandle,true);
                    return true;
                }
                else
                {
                    $this->ftpError = 'authentication failed';
                    return false;
                }

            }
            else
            {
                $this->ftpError = 'connection failed';
                return false;
            }
        }
        return false;
    }

    public function changeDirectory($directory)
    {
        if($this->ftpHandle)
        {
            if(@ftp_chdir($this->ftpHandle,$directory) != false)
                return true;
            else
            {
                $this->ftpError = 'fail to change directory';
                return false;
            }

        }
        else
        {
            $this->ftpError = 'no ftp connection handle';
            return false;
        }
    }

    public function makeDirectory($directory)
    {
        if($this->ftpHandle)
        {
            $origin = ftp_pwd($this->ftpHandle);

            if(!@ftp_chdir($this->ftpHandle,$directory))
            {
                if(@ftp_mkdir($this->ftpHandle,$directory) != false)
                    return true;
                else
                {
                    $this->ftpError = 'fail to make directory';
                    return false;
                }
            }
            else
            {
                ftp_chdir($this->ftpHandle,$origin);
                return true;
            }

        }
        else
        {
            $this->ftpError = 'no ftp connection handle';
            return false;
        }
    }

    public function deleteDirectory($directory)
    {
        if($this->ftpHandle)
        {
            if(@ftp_rmdir($this->ftpHandle,$directory) != false)
                return true;
            else
            {
                $this->ftpError = 'fail to make directory';
                return false;
            }
        }
        else
        {
            $this->ftpError = 'no ftp connection handle';
            return false;
        }
    }

    public function downloadFile($localFile, $remoteFile, $mode)
    {
        if($this->ftpHandle)
        {
            if(@ftp_get($this->ftpHandle,$localFile,$remoteFile,$mode) != false)
                return true;
            else
            {
                $this->ftpError = 'fail to download file';
                return false;
            }
        }
        else
        {
            $this->ftpError = 'no ftp connection handle';
            return false;
        }
    }

    public function uploadFile($localFile, $remoteFile, $mode)
    {
        if($this->ftpHandle)
        {
            if(@ftp_put($this->ftpHandle,$remoteFile,$localFile,$mode) != false)
                return true;
            else
            {
                $this->ftpError = 'fail to upload file';
                return false;
            }
        }
        else
        {
            $this->ftpError = 'no ftp connection handle';
            return false;
        }
    }

    public function deleteFile($file)
    {
        if($this->ftpHandle)
        {
            if( $this->ftpFileExists($file))
            {
                if(@ftp_delete($this->ftpHandle,$file) != false)
                    return true;
                else
                {
                    $this->ftpError = 'fail to delete file';
                    return false;
                }
            }
        }
        else
        {
            $this->ftpError = 'no ftp connection handle';
            return false;
        }
    }

    public function renameFile($oldName, $newName)
    {
        if($this->ftpHandle)
        {
            if(@ftp_rename($this->ftpHandle,$oldName,$newName) != false)
                return true;
            else
            {
                $this->ftpError = 'fail to rename file';
                return false;
            }
        }
        else
        {
            $this->ftpError = 'no ftp connection handle';
            return false;
        }
    }

    public function uploadParameterFile(Tpe $tpe)
    {
        if($this->connect() === true)
        {
            if($this->makeDirectory($tpe->getTpeNumber()) === true)
            {
                if($this->changeDirectory($tpe->getTpeNumber()) === true)
                {
                    $this->uploadFile($tpe->getTpeParameters()->getFileName(),'PARAM.PAR',FTP_ASCII);
                    return true;
                }
                return false;
            }
            return false;
        }

        return false;

    }

    public function downloadDataFile($localDir)
    {


        if($this->connect() === true)
        {
            $content = ftp_nlist($this->ftpHandle,$origin = ftp_pwd($this->ftpHandle));
            foreach($content as $dir)
            {
                if(!file_exists($localDir.'/'.$dir))
                    mkdir($localDir.'/'.$dir,0777,true);

                $this->changeDirectory($dir);
                $contentFile = ftp_nlist($this->ftpHandle,ftp_pwd($this->ftpHandle));
                foreach($contentFile as $dataFile)
                {
                    $extension = strrchr($dataFile,'.');
                    if($extension != '.PAR')
                        $this->downloadFile($localDir.'/'.$dataFile,$dataFile,FTP_ASCII);
                }
                $this->changeDirectory($origin);
            }

            return true;
        }

        return false;
    }

    public function ftpFileExists($file)
    {
        $content = ftp_nlist($this->ftpHandle,ftp_pwd($this->ftpHandle));
        if(in_array($file,$content))
            return true;
        else
            return false;
    }

} 