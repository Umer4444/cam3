<?php
namespace Application\Extended\Rbcomment\Form;

use RbComment\Form\CommentForm;

class CustomCommentForm extends CommentForm
{

    public function __construct(array $strings)
    {
        parent::__construct($strings);

        $this->add(array(
            'name' => 'parent_id',
            'attributes' => array(
                'id' => 'parentId',
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'content',
            'attributes' => array(
                'placeholder' => $strings['content'],
            ),
        ));


    }

}