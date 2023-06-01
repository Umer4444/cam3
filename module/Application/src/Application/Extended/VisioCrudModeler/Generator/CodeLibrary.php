<?php

namespace Application\Extended\VisioCrudModeler\Generator;

use VisioCrudModeler\Generator\CodeLibrary as VisioCodeLibrary;


class CodeLibrary extends VisioCodeLibrary
{

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();

        $this->library['controller.createAction.body'] = '$form = $this->getServiceLocator()->get(\'%form%\');
$request = $this->getRequest();

$filter = $this->getServiceLocator()->get(\'%filter%\');
$form->setInputFilter($filter->getInputFilter());

$row = new $this->entity();
$em = $this->getServiceLocator()->get("em");
$form->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($em));
$form->setObject($row);
$form->bind($row);

if ($request->isPost()) {

    $form->setData($request->getPost());

    if ($form->isValid()) {

        $em->persist($row);
        $em->flush();

        // Redirect to list of albums
        return $this->redirect()->toUrl(\'/%filteredModule%/%filteredController%/list\');
    }
}
return array(\'form\' => $form);';

        $this->library['controller.deleteAction.body'] = '$id = (int) $this->params()->fromRoute(\'id\', 0);
if (!$id) {
    return $this->redirect()->toUrl(\'/%filteredModule%/%filteredController%/list\');
}
$em = $this->getServiceLocator()->get("em");
$em->remove($em->find($this->entity, $id));
$em->flush();

return $this->redirect()->toUrl(\'/%filteredModule%/%filteredController%/list\');';

        $this->library['grid.init.body'] = 'foreach (get_class_methods($this) as $method) { if (substr($method, 0, 2) == "on") {$this->$method();}} '."\n".
                                    '$this->getHeader("edit")->getCell()->addDecorator("callable", array('."\n".
                                    '    "callable" => function($context, $record){'."\n".
                                    '        return sprintf("<a href=\"%s\">Edit</a>", $record["%s"]);'."\n".
                                    '    }'."\n".
                                    '));'."\n\n".
                                    '$this->getHeader("delete")->getCell()->addDecorator("callable", array('."\n".
                                    '    "callable" => function($context, $record){'."\n".
                                    '        return sprintf("<a href=\"%s\">Delete</a>", $record["%s"]);'."\n".
                                    '    }'."\n".
                                    '));';

        $this->library['form.constructor.field.submit'] = "\$this->add(array(\n".
                                    "    'name' => 'submit',\n".
                                    "    'attributes' => array(\n".
                                    "        'type'  => 'submit',\n".
                                    "        'value' => 'Go',\n".
                                    "        'id' => 'submitbutton',\n".
                                    "        'class' => 'form-control btn-success',\n".
                                    "        'style' => 'width: 50%'\n".
                                    "    ),\n".
                                    "), ['priority' => -1000]);\n";

        $this->library['controller.ajaxListAction.body'] = '
$em = $this->getServiceLocator()->get("em");
$queryBuilder = $em->createQueryBuilder();

$queryBuilder->add("select", "r")->add("from", $this->entity.\' r\')->orderBy(\'r.id\', \'DESC\');

$table = $this->getServiceLocator()->get(\'%grid%\');
$table->setSource($queryBuilder)->setParamAdapter($this->getRequest()->getPost());

return $this->htmlResponse($table->render());';

        $this->library['controller.updateAction.body'] = '$id = (int) $this->params()->fromRoute(\'id\', 0);
if (!$id) {
    return $this->redirect()->toUrl(\'/%filteredModule%/%filteredController%/list\');
}

$form = $this->getServiceLocator()->get(\'%form%\');
$filter =$this->getServiceLocator()->get(\'%filter%\');
$form->setInputFilter($filter->getInputFilter());

$em = $this->getServiceLocator()->get("em");
$row = $em->find($this->entity, $id);
$form->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($em));
$form->bind($row);

$request = $this->getRequest();
if ($request->isPost()) {

    $form->setData($request->getPost());

    if ($form->isValid()) {
        $em->flush();
        return $this->redirect()->toUrl(\'/%filteredModule%/%filteredController%/list\');
    }
}

return array(
    \'id\' => $id,
    \'form\' => $form,
);';

        $this->library = str_replace(
            [
                "toUrl('/",
                "\$table = new %grid%();",
                '<a href=\"%s\">Edit</a>", $record["%s"]);',
                '<a href=\"%s\">Delete</a>", $record["%s"]);'
            ],
            [
                "toUrl('/admin/",
                "\$table = \$this->getServiceLocator()->get('%grid%');",
                '<a href=\"/admin%s\">Edit</a>", $record->getId());//%s',
                '<a href=\"/admin%s\">Delete</a>", $record->getId());'
            ],
            $this->library
        );

    }

}