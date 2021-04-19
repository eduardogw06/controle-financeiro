<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Model\People;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController{
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function indexAction(){
        $people = new People($this->db);
        $dataPeople = $people->list();
        
        return new ViewModel([
            'people' => $dataPeople
        ]);
    }

    public function addAction(){
        return new ViewModel();
    }

    public function saveAction(){
        $request = $this->getRequest();
        
        if($request->isPost()){
            $postFields = $this->params()->fromPost();
            
            $people = new People($this->db, $postFields);
            $response = $people->save();

            return new JsonModel($response);
        }
    }

    


    
}
