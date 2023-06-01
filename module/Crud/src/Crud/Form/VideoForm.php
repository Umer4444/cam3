<?php

namespace Crud\Form;

use Crud\Traits;
use PerfectWeb\Core\Traits as PerfectWebTraits;

class VideoForm extends BaseForm\BaseVideoForm
{

    use PerfectWebTraits\EntityManager;
    use Traits\Status;
    use Traits\Tags;
    use Traits\Category;

    public function __construct($sl)
    {

        $this->setServiceLocator($sl);
        parent::__construct();

        $this->remove('id');
        $this->remove('duration');
        $this->remove('uploaded_on');
        $this->remove('user');
        $this->remove('filename');
        $this->remove('category_id');
        $this->remove('reference_id');
        $this->remove('entity');
        $this->remove('status');
        $this->remove('cover');
        $this->remove('tags');

        $this->addFormTags();
        $this->get('start_date')->setOptions(['label' => 'Start Date'])->setAttribute('data-type', 'datetime');

        $this->addFormStatus();

        $this->add($this->getFormCategory(\Videos\Entity\VideoCategory::class));

    }

}