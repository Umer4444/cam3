<?php
/**
 * This file is generated automatically for table "webchat_history". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Form\BaseForm;

class BaseWebchatHistoryForm extends \VisioCrudModeler\Form\AbstractForm
{

    public function __construct()
    {
        parent::__construct('webchat_history');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Id',
            ),
        ));

        $this->add(array(
            'name' => 'user_id',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'UserId',
            ),
        ));

        $this->add(array(
            'name' => 'start',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Start',
            ),
        ));

        $this->add(array(
            'name' => 'end',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'End',
            ),
        ));

        $this->add(array(
            'name' => 'rating_surround',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'RatingSurround',
            ),
        ));

        $this->add(array(
            'name' => 'votes_surround',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'VotesSurround',
            ),
        ));

        $this->add(array(
            'name' => 'rating_appearance',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'RatingAppearance',
            ),
        ));

        $this->add(array(
            'name' => 'votes_appearance',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'VotesAppearance',
            ),
        ));

        $this->add(array(
            'name' => 'rating_sound',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'RatingSound',
            ),
        ));

        $this->add(array(
            'name' => 'votes_sound',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'VotesSound',
            ),
        ));

        $this->add(array(
            'name' => 'rating_light',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'RatingLight',
            ),
        ));

        $this->add(array(
            'name' => 'votes_light',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'VotesLight',
            ),
        ));

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

