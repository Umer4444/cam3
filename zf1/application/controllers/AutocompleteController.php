<?php

class AutocompleteController extends App_Controller_Action
{

    public function init()
    {
        parent::init();

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
    }

    public function regionsAction()
    {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $country_id = (int)$request->getParam('country');

        if ($request->getPost()) {

            $query = $request->getPost('q');
            $this->load('countries_regions');
            $this->getResponse()->setHttpResponseCode(200);
            echo $this->countries_regions->regionsAutocomplete($country_id, $query);

            exit;
        }
    }

    public function citiesAction()
    {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $region_id = (int)$request->getParam('region');

        if ($request->getPost()) {
            $this->getResponse()->setHttpResponseCode(200);
            $query = $request->getPost('q');
            $this->load('countries_cities');
            echo $this->countries_cities->citiesAutocomplete($region_id, $query);

            exit;
        }
    }

    public function locationAction()
    {

        $type = 'co';
        $request = $this->getRequest();

        $get_type = $request->getParam('type');
        if ($get_type == 'country')
            $type = 'co';
        elseif ($get_type == 'region')
            $type = 're';
        elseif ($get_type == 'city')
            $type = 'ci';

        if ($request->getParam('id'))
            $in_location = (int)$request->getParam('id');
        else
            $in_location = null;

        if ($request->getPost()) {

            $query = $request->getPost('q');

            $this->load('countries');
            // makes disable renderer

          $this->_helper->viewRenderer->setNoRender();

          $this->_helper->getHelper('layout')->disableLayout();

            echo $this->countries->locationAutocomplete($type, $query, $in_location);
            exit;
        } else {
            throw new \Exception('not post!');
        }
    }

    public function usernameAction()
    {
        $this->getResponse()->setHttpResponseCode(200);
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        if ($request->getPost()) {

            $query = $request->getPost('q');
            $this->load('user');
            echo $this->user->usernameAutocomplete($query);
            exit;
        }
    }

    public function sendtousersAction()
    {
        $this->getResponse()->setHttpResponseCode(200);
        $request = $this->getRequest();
        if ($request->getPost()) {
            $this->load("moderator");
            $this->load("user");
            $this->load("model");

            $query = $request->getPost('q');

            $users = $this->user->usernameSuggest($query);
            $models = $this->model->usernameSuggest($query);
            $moderators = $this->moderator->usernameSuggest($query);

            echo json_encode(array_merge($users, $models, $moderators));
        }
    }

    public function quotesAction()
    {
        $request = $this->getRequest();

        $guery = $request->getParam('term');

        if ($query) {
            $this->load("model_quotes");
            $quotes = $this->model_quotes->quoteSuggest($query);

            echo json_encode($quotes);
        } else {
            echo json_encode(array());
        }

    }
    /*    protected function disableLayout() {
            if ($this->_helper->hasHelper('layout'))
                $this->_helper->layout->disableLayout();
            return $this;
        }

        protected function disableView() {
            $this->getFrontController()->setParam('noViewRenderer', true);
            return $this;
        }
                          */
}

