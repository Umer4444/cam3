<?php
namespace Images\Controller;

use CgmConfigAdmin\Service\ConfigAdmin as ConfigAdminService;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Images\Form;
use Images\Model;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\Size;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package Images\Controller
 *
 * IndexController for images
 */
class IndexController extends AbstractActionController
{

    public function albumsAction()
    {}

    public function albumAction()
    {}

    public function imageAction()
    {}

    public function mediaAction()
    {}

    public function recentAction()
    {

        /** @var \Images\Paginator\ImagesPaginator $paginator */
        $paginator = $this->getServiceLocator()->get(\Images\Paginator\ImagesPaginator::class);
        $paginator->setData(array_merge($this->params()->fromRoute(), ['route' => true]));

        return new ViewModel(
            ['paginator' => $paginator]
        );

    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAlbumAction()
    {

        $sm = $this->getServiceLocator();
        $entityManager = $sm->get('Doctrine\ORM\EntityManager');

        $form = new \Images\Form\NewAlbum('newAlbum', $entityManager); //initializing the form
        $add = new \Images\Entity\Albums;
        $form->bind($add);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $albumsFilter = new \Images\Model\imageFilters();
            $form->setInputFilter($albumsFilter->getInputFilter());
            $nonFile = $request->getPost()->toArray();
            $File = $this->params()->fromFiles('cover'); //checking if a file was selected
            $validator = new \Zend\Validator\File\IsImage();
            $size = new Size(array('min' => 1)); //minimum bytes filesize

            $adapter = new \Zend\File\Transfer\Adapter\Http();

            $adapter->setValidators(array($size), $File['name'], $validator);

            $newName = md5(microtime());

            $extension = explode('.', $File['name']);

            $extension = $extension[count($extension) - 1];

            $rename = $newName;

            //giving the upload a random name based on md5 of microtime
            $imagename = array('cover' => $rename . '.' . $extension);
            $adapter->addValidator('Extension', true, array('jpg', 'png', 'jpeg', 'gif'));
            $adapter->addValidator('IsImage', true);
            $adapter->addFilter(
                'Rename',
                $rename

            );

            if (!$adapter->isValid()) {
                //if the fileupload adapter is not valid(no file was uploaded)

                $dataError = $adapter->getMessages();
                $error = array();
                foreach ($dataError as $key => $row) {
                    $error[] = $row;
                }
                $form->setMessages(array('cover' => $error));
                // return $this->redirect()->toRoute('list-images');
            }

            $modelId['modelId'] = $this->zfcUserAuthentication()->getIdentity();

            $data = array_merge_recursive(
                $nonFile,
                $modelId,
                $imagename
            );
            $form->setValidationGroup('name', 'description', 'modelId', 'cover');

            $form->setData($data);

            if ($form->isValid()) {
                if ($adapter->isValid()) {

                    $adapter->setDestination(ROOT_PATH . '/public/uploads/covers');

                    if ($adapter->receive($File['name'])) {
                        $size = $sm->get('cgmconfigadmin')->getConfigValue('WebinoImageThumb/size');
                        $custom = $sm->get('cgmconfigadmin')->getConfigValue('WebinoImageThumb/test');

                        if ($size == 'custom') {

                            $settingValue = $custom;

                        } else {

                            $settingValue = $size;

                        }

                        $proportions = $sm->get('cgmconfigadmin')
                            ->getConfigValue
                            ('WebinoImageThumb/Do you want to constrain proportions?' .
                                ' (if you select no, the images will be cropped)');

                        $sizes = explode("x", $settingValue);
                        $width = $sizes[0];
                        $height = $sizes[1];
                        $file = $request->getFiles();
                        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
                        $imagePath = ROOT_PATH . '/public/uploads/covers/' . $rename;
                        $thumb = $thumbnailer->create($imagePath, $options = array(), $plugins = array());

                        if ($proportions == 1) {

                            $thumb->resize(
                                $width,
                                $height
                            );

                        } else {

                            $thumb->adaptiveResize(
                                $width,
                                $height
                            );

                        }

                        $thumb->save(ROOT_PATH . '/public/uploads/covers/' . $rename);
                        //actually saving the resized image, overwriting the original one
                    }

                    $entityManager->persist($add);
                    $entityManager->flush(); //adding values to database
                    $request = new Request();
                    $file = $request->getFiles();

                    rename(ROOT_PATH . "/public/uploads/covers/" .
                        $rename, ROOT_PATH . "/public/uploads/covers/" .
                        $rename . '.' . $extension);
                    return $this->redirect()->toRoute('media');

                } else {

                    $entityManager->persist($add);
                    $entityManager->flush(); //adding values to database

                    return $this->redirect()->toRoute('media');
                }

            }

        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addImageAction()
    {
        $sm = $this->getServiceLocator();
        $entityManager = $sm->get('Doctrine\ORM\EntityManager');

        $form = new \Images\Form\NewImage('newImage', $entityManager, $this->zfcUserAuthentication()->getIdentity()); //initializing the form

        $add = new \Images\Entity\Photo(); //ads entity containing ads name, type, size, etc

        $form->bind($add);

        $request = $this->getRequest();

        if ($request->isPost()) { //cheking if form is post

            $imageFilter = new \Images\Model\imageFilters(); //adding the filters from /model/AdFilters

            $form->setInputFilter($imageFilter->getInputFilter());
            $nonFile = $request->getPost()->toArray();

            $File = $this->params()->fromFiles('extension'); //checking if a file was selected
            $validator = new \Zend\Validator\File\IsImage();
            $size = new Size(array('min' => 1)); //minimum bytes filesize

            $adapter = new \Zend\File\Transfer\Adapter\Http();

            $adapter->setValidators(array($size), $File['name'], $validator);

            $extension = explode('.', $File['name']);

            $extension = $extension[count($extension) - 1];

            $rename = $extension;

            //giving the upload a random name based on md5 of microtime

            //  $adapter->addValidator('Extension', true, array('jpg', 'png', 'jpeg', 'gif'));
            $adapter->addValidator('IsImage', true);
            // $adapter->addFilter
            //            (
            //                'Rename',
            //                $rename
            //
            //            );

            if (!$adapter->isValid()) {
                //if the fileupload adapter is not valid(no file was uploaded)

                $dataError = $adapter->getMessages();
                $error = array();
                foreach ($dataError as $key => $row) {
                    $error[] = $row;
                }
                $form->setMessages(array('extension' => $error));
                // return $this->redirect()->toRoute('list-images');
            }

            $imagename = array('extension' => $rename);
            //setting the name to the rename variable from above(md5 of microctime)
            $modelId['modelId'] = $this->zfcUserAuthentication()->getIdentity();

            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $modelId,
                $imagename
            );

            $form->setValidationGroup('name', 'description', 'extension', 'albumId', 'modelId');

            $form->setData($data);

            if ($form->isValid()) {

                if ($adapter->isValid()) {

                    //generating an array with all banner sizes to be able to resize the uploaded pic

                    $adapter->setDestination(ROOT_PATH . '/public/uploads/images');

                    if ($adapter->receive($File['name'])) {
                        $size = $sm->get('cfg')->getConfigValue('WebinoImageThumb/size');
                        $custom = $sm->get('cfg')->getConfigValue('WebinoImageThumb/test');

                        if ($size == 'custom') {

                            $settingValue = $custom;

                        } else {

                            $settingValue = $size;

                        }

                        $proportions = $sm->get('cfg')
                            ->getConfigValue('WebinoImageThumb/Do you want to constrain proportions?'
                                . ' (if you select no, the images will be cropped)');

                        $sizes = explode("x", $settingValue);
                        $width = $sizes[0];
                        $height = $sizes[1];
                        $file = $request->getFiles();
                        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
                        $imagePath = ROOT_PATH . '/public/uploads/images/' . $file['extension']['name'];
                        $thumb = $thumbnailer->create($imagePath, $options = array(), $plugins = array());

                        if ($proportions == 1) {

                            $thumb->resize(
                                $width,
                                $height
                            );

                        } else {

                            $thumb->adaptiveResize(
                                $width,
                                $height
                            );

                        }

                        $thumb->save(ROOT_PATH . '/public/uploads/images/' . $file['extension']['name']);
                        //actually saving the resized image, overwriting the original one
                    }

                    $entityManager->persist($add);
                    $entityManager->flush(); //adding values to database
                    $request = new Request();
                    $file = $request->getFiles();
                    $extension = explode('.', $file['extension']['name']);

                    $extension = $extension[count($extension) - 1];
                    rename(ROOT_PATH . "/public/uploads/images/" .
                        $file['extension']['name'], ROOT_PATH . "/public/uploads/images/" .
                        $add->getId() . '.' . $extension);
                    return $this->redirect()->toRoute('media');

                }

            }

        }

        return new ViewModel(array(
            'form' => $form
        ));

    }

    /**
     * @return ViewModel
     */
    public function imagesListAction()
    {

        $source = $this->getSource();
        $table = new \Images\Model\imagesTable();
        $paramsAdapter = new \ZfTable\Params\AdapterArrayObject($this->getRequest()->getPost());

        $table->setAdapter($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'))
            ->setSource($source)
            ->setParamAdapter($paramsAdapter);

        $viewData = array(
            'tableDataTable' => $table

        );

        return new ViewModel($viewData);

    }

    /**
     * @return mixed
     * private function!
     */
    private function getSource()
    {
        return $this->getTableData()->fetchAllSelect();
    }

    /**
     * @return array|object
     */
    public function getTableData()
    {

        $sm = $this->getServiceLocator();

        $this->tabledata = $sm->get('Images\Model\imagesTableGateway');

        return $this->tabledata;

    }

    /**
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function imagesAjaxAction()
    {

        $table = new \Images\Model\imagesTable;
        $paramsAdapter = new \ZfTable\Params\AdapterArrayObject($this->getRequest()->getPost());
        $table->setAdapter($this->getDbAdapter())
            ->setSource($this->getSource())
            ->setParamAdapter($paramsAdapter);

        return $this->htmlResponse($table->render());

    }

    /**
     * @return array|object
     */
    public function getDbAdapter()
    {
        if (!$this->dbAdapter) {
            $sm = $this->getServiceLocator();
            $this->dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->dbAdapter;
    }

    /**
     * @param $html
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function htmlResponse($html)
    {
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent($html);
        return $response;
    }

    /**
     * @return JsonModel|ViewModel
     */
    public function imagesDeleteAction()
    {

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('list-images');
        }
        $source = $this->getSource();
        $table = new \Images\Model\imagesTable; //initializing table
        $paramsAdapter = new \ZfTable\Params\AdapterArrayObject($this->getRequest()->getPost());

        $table->setAdapter($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'))
            ->setSource($source)
            ->setParamAdapter($paramsAdapter);
        //initializing the database adapter

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
            $response = array();
            if ($del == 'Yes') { //if user selected: YES - > then delete the image from database and form

                $id = (int)$request->getPost('id');
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $image = $objectManager->getRepository('Images\Entity\Images')->find($this->params('id'));

                $objectManager->remove($image);
                $objectManager->flush();
                $response = array('status' => 'success');

            }
            return new JsonModel($response);

        }

        $viewModel = new ViewModel(
            array(
                'tableDataTable' => $table,
                'id' => $id
            )
        );
        $viewModel->setTemplate('images/index/images-list.phtml');
        return $viewModel;

    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function imagesEditAction()
    {

        $id = (int)$this->params()->fromRoute('id', 0); //getting the ad id from route

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $imagesEntity = $objectManager->getRepository('Images\Entity\Images');
        $images = $imagesEntity->findOneBy(array('id' => $id));
        $picture = $images->getExtension(); //getting the fileupload

        $flush = $imagesEntity->findOneBy(array('id' => $id));

        $form = new \Images\Form\NewImage('image', $objectManager);
        $form->setHydrator(new DoctrineHydrator($objectManager, 'Images\Entity\Images'));
        $form->bind($images);
        $form->get('extension')->setValue($id . '.' . $picture);

        if ($this->getRequest()->isPost()) {

            $ad = new \Images\Model\imageFilters;

            $form->setInputFilter($ad->getInputFilter());

            $nonFile = $this->getRequest()->getPost()->toArray();
            $File = $this->params()->fromFiles('extension');
            //$fileupload = $this->params()->fromFiles('extension');

            if (!isset($fileupload)) {

                $File = $id . '.' . $picture;
            } else {

                $File = $fileupload;
            };
            $adapter = new \Zend\File\Transfer\Adapter\Http();

            $extension = explode('.', $File);

            $extension = $extension[count($extension) - 1];

            $rename = array('extension' => $extension);

            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $rename
            );

            $form->setValidationGroup('name', 'description', 'extension', 'categoryId');
            $form->setData($data);
            if ($form->isValid()) {

                if ($adapter->isValid()) {

                    //generating an array with all banner sizes to be able to resize the uploaded pic

                    $adapter->setDestination(ROOT_PATH . '/public/uploads/images');

                    if ($adapter->receive($File)) {
                        $sm = $this->getServiceLocator();
                        $size = $sm->get('cgmconfigadmin')->getConfigValue('WebinoImageThumb/size');
                        $custom = $sm->get('cgmconfigadmin')->getConfigValue('WebinoImageThumb/test');

                        if ($size == 'custom') {

                            $settingValue = $custom;

                        } else {

                            $settingValue = $size;

                        }

                        $proportions = $sm->get('cgmconfigadmin')
                            ->getConfigValue('WebinoImageThumb/Do you want to constrain proportions?' .
                                ' (if you select no, the images will be cropped)');

                        $sizes = explode("x", $settingValue);
                        $width = $sizes[0];
                        $height = $sizes[1];
                        $request = $this->getRequest();
                        $file = $request->getFiles();
                        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
                        $imagePath = ROOT_PATH . '/public/uploads/images/' . $file['extension']['name'];
                        $thumb = $thumbnailer->create($imagePath, $options = array(), $plugins = array());

                        if ($proportions == 1) {

                            $thumb->resize(
                                $width,
                                $height
                            );

                        } else {

                            $thumb->adaptiveResize(
                                $width,
                                $height
                            );

                        }

                        $thumb->save(ROOT_PATH . '/public/uploads/images/' . $file['extension']['name']);
                        //actually saving the resized image, overwriting the original one
                    }

                }

                $objectManager->persist($flush);
                $objectManager->flush(); //adding values to database
                $request = new Request();
                $file = $request->getFiles();

                if ($file['name'] != null) {

                    $add = new \Images\Entity\Images;
                    $extension = explode('.', $file['extension']['name']);

                    $extension = $extension[count($extension) - 1];
                    rename(ROOT_PATH . "/public/uploads/images/" .
                        $file['extension']['name'], ROOT_PATH .
                        "/public/uploads/images/" . $add->getId() . '.' . $extension);

                }

                return $this->redirect()->toRoute('list-images');
            }

        }

        $ViewModel = new ViewModel(
            array(
                'form' => $form,
                'uploaded' => $picture,

            )
        );

        $ViewModel->setTemplate('layout/editimages');
        return $ViewModel;

    }

     /**
     * @return array|\Zend\Http\Response
     */
    public function cgmConfigAdminAction()
    {

        $service = $this->getConfigAdminService();

        $images = array(
            'WebinoImageThumb' => $service->getConfigGroups()['WebinoImageThumb'],
            'solo.ionut.dev.perfectweb.ro' => $service->getConfigGroups()['solo.ionut.dev.perfectweb.ro']
        );
        $service->setConfigGroups($images);
        // $links = array($this->configAdminService =
        //$this->getServiceLocator()->get('cgmconfigadmin')->getConfigGroups()['WebinoImageThumb']);
        // $service->setConfigGroups($links);
        //        $service->getConfigGroups();

        if ($this->request->isPost()) {
            $config = $this->request->getPost();

            $successful = false;
            if (!empty($config['preview'])) {
                if ($service->previewConfigValues($config)) {
                    $message = '<strong>Ready to preview</strong> ';
                    $message .= 'You may navigate the site to test your changes. ';
                    $message .= '<div><em>The changes will not be made permanent until saved.</em></div>';
                    $message = array('message' => $message, 'type' => 'info');
                    $successful = true;
                }

            } else if (!empty($config['reset'])) {

                $service->resetConfigValues();
                $message = '<strong>Preview Settings have been reset</strong> ';
                $message = array('message' => $message);
                $successful = true;

            } else if (!empty($config['save'])) {

                if ($service->saveConfigValues($config)) {
                    $message = '<strong>Settings have been saved</strong> ';
                    $message = array('message' => $message, 'type' => 'success');
                    $successful = true;
                }
            }

            if ($successful) {

                $this->flashMessenger()
                    ->setNamespace('cgmconfigadmin')
                    ->addMessage($message);
                return $this->redirect()->toRoute();
            }
        }

        return array(
            'form' => $service->getConfigOptionsForm(),
        );

    }

    /**
     * @return array|object
     */
    public function getConfigAdminService()
    {
        if (!isset($this->configAdminService)) {
            $this->configAdminService = $this->getServiceLocator()->get('site.cfg');
        }
        return $this->configAdminService;
    }

    /**
     * @param  ConfigAdminService $service
     * @return ConfigOptionsController
     */
    public function setConfigAdminService(ConfigAdminService $service)
    {
        $this->configAdminService = $service;
        return $this;
    }

    /**
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function updateRowAction()
    {
        $param = $this->getRequest()->getPost();

        $this->getTableData()
            ->update(array($param['column'] => $param['value']), 'id =' . $param['row']);

        $response = $this->getResponse();
        $response->setStatusCode(200);
        if(!isset($html)) $html = '';
        $response->setContent($html);
        return $response;

    }

}
