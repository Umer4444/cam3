<?php

namespace Crud\Form;

use Crud\Traits;
use PerfectWeb\Core\Traits as PerfectWebTraits;

class PhotoForm extends BaseForm\BasePhotoForm
{

    use PerfectWebTraits\EntityManager;
    use Traits\Status;

    public function __construct($sl)
    {

        $this->setServiceLocator($sl);
        parent::__construct();

        $this->remove('id');
        $this->remove('album_id');
        $this->remove('user');
        $this->remove('filename');
        $this->remove('reference_id');
        $this->remove('uploaded_on');
        $this->remove('entity');
        $this->remove('status');
        $this->remove('type');
        $this->remove('position');

        $this->addFormStatus();

    }

}