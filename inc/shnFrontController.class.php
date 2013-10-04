<?php

/**
 * shnFrontController is the main Controler of the system which is 
 * responsible for 
 *   1. decoding url.
 *   2. loading the module.
 *   3. loading the view.
 *   4. performing the action.
 *   5. rendering the content.
 *
 * Copyright (C) 2009
 *   Respere Lanka (PVT) Ltd. http://respere.com, info@respere.com
 * Copyright (C) 2009
 *   Human Rights Information and Documentation Systems,
 *   HURIDOCS), http://www.huridocs.org/, info@huridocs.org
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @auther  H J P Fonseka <jo@respere.com>
 * @package Framework
 * 
 */
class shnFrontController {

    //no need to hide the following these should be accessible throughout the system. 
            public
            $request = NULL,
            $module = NULL,
            $view = NULL,
            $action = NULL;
    //member to hold the instance of controler
    private static $controller;

    public function __construct() {
        $this->request = shnRequest::getRequest();
    }

    /**
     * getController return an instance of controler class. This function follows singleton
     * pattern 
     * 
     * @static
     * @access public
     * @return void
     */
    static public function getController() {
        if (!isset(self::$controller)) {
            self::$controller = new shnFrontController();
        }

        return self::$controller;
    }

    /**
     * getView return an instance of the view  
     * 
     * @static
     * @access public
     * @return void
     */
    static public function getView() {
        return self::$controller->view;
    }

    /**
     * getModule return an instance of the module 
     * 
     * @static
     * @access public
     * @return void
     */
    static public function getModule() {
        return self::$controller->module;
    }

    /**
     * setDefaultModule set the module to default within the controler 
     * 
     * @static
     * @access public
     * @return void
     */
    static public function loadDefaultModule() {
        $module = new shnModule();
        self::$controller->module = $module;
    }

    /**
     * loadDefaultView will load the HTML view ot the controler 
     * 
     * @static
     * @access public
     * @return void
     */
    static public function loadDefaultView() {
        $view = new shnView_HTML();
        self::$controller->view = $view;
    }

    public function setAction($action = null) {
        $this->action = $action;
    }

    /**
     * loadRequestModule load the requested module according to uri 
     * 
     * @access public
     * @return void
     */
    public function loadRequestModule() {
        $module = $this->request->module;
        $file = APPROOT . "mod/$module/{$module}Module.class.php";
        if (!file_exists($file))
            throw new shn404Exception();

        include_once($file);

        //check and create an instance of the module action class
        $class = $module . 'Module';
        if (!class_exists($class))
            throw new shn404Exception();

        $instance = new $class();

        //check if the module action class is an instance of shnModule
        if (!($instance instanceof shnModule))
            throw new shn404Exception();

        //load the instance to controler
        $this->module = $instance;
    }

    /**
     * _load_view() will load the view object to the controler.
     * Function follows the singleton pattern since we do not need
     * more than one view object for a single request.
     * 
     * @access protected
     * @return void
     */
    protected function loadRequestView() {
        $view = $this->request->view;
        //load the view class to match the stream type
        $class = "shnView_" . strtoupper($view);
        if (!class_exists($class))
            throw new shn404Exception();

        $instance = new $class($this);

        //check if the class is a view instance
        if (!($instance instanceof shnView))
            throw new shn404Exception();

        //load the instance to controler
        $this->view = $instance;
    }

    /**
     * dispatch this is where the resource will be called and the content will be generated
     * 
     * @access public
     * @return void
     */
    public function dispatch() {
        global $global;

        //load request module
        $this->loadRequestModule();
        //load the request action
        $this->setAction($this->request->action);
        //load request view
        $this->loadRequestView();
        //generate and send responce
        $this->view->setTemplate($this->request->tpl);

        //wait a minit before we dispatch lets check for permissions
        include_once APPROOT . 'inc/security/lib_acl.inc';

        if ($this->request->module != 'home' || $this->request->action != 'download') {
            acl_mod_allowed($this->request->module);
        }
        $this->sendResponce();
    }

    public function sendResponce() {
        $action = 'act_' . $this->action;
        //check if the method exists
        if (!method_exists($this->module, $action))
            throw new shn404Exception();
        //execute the action and change the module state
        $this->module->$action();
        //get model data
        $data = get_object_vars($this->module);
        //send data to view
        $this->view->setData($data);
        //render data
        $this->view->render();
    }

}
