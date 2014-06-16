<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of smeInv_sysinfo
 *
 * @author jason
 */
class smeInv_sysinfo {
    public $versionNum = '0.5';
    private $creditDev = "Jason Shen";

    

    public function dispVersion(){
        echo $this->versionNum;
    }

    public function dispDeveloper(){
        echo $this->creditDev;
    }
    
    
}
?>
