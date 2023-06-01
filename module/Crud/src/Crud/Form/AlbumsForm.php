<?php

namespace Crud\Form;

use Crud\Traits;
use PerfectWeb\Core\Traits as PerfectWebTraits;

class AlbumsForm extends \VisioCrudModeler\Form\AbstractForm
{

    use Traits\Status;
    use Traits\Category;
    use Traits\Tags;
    use PerfectWebTraits\EntityManager;

    public function __construct($sl)
    {

        $this->setServiceLocator($sl);

        parent::__construct('albums');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));

        $this->add($this->getFormCategory(\Images\Entity\Albums::class));

        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Description',
            ),
        ));

        $this->addFormTags();

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));


        $this->add(array(
            'name' => 'cost',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Cost',
            ),
        ));

        $this->addFormStatus();

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'form-control btn-success',
                'style' => 'width: 50%'
            ),
        ), ['priority' => -1000]);
    }


}

