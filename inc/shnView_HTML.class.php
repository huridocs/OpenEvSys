<?php
/**
 * shnView_HTML 
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

class shnView_HTML extends shnView
{

    protected $template;
    /**
     * render print the responce content 
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        $content = $this->getContent();
        //include the main template
        function include_section($section_name)
        {
            $view = shnFrontController::getView();
            $view->include_section($section_name);
        }
        
        //append messages to content
        $messages = shnMessageQueue::renderMessages();
        $content = $messages . $content;

        include_once APPROOT.'tpls/html_responce.php';
    }

    /**
     * get_content 
     * 
     * @access public
     * @return void
     */
    protected function getContent()
    {
        extract($this->data);

        $tpl = $this->getTemplatePath();
        //load the template
        ob_start();
        include_once($tpl);
        $content = ob_get_clean();
        // we need to clear any global get args set by the modules
        clear_url_args();
        return $content;
    }

    /**
     * include_section 
     * 
     * @param mixed $section_name 
     * @access protected
     * @return void
     */
    public function include_section($section_name)
    {
        //check if a module overide exists
        $this->controller = shnFrontController::getController();
        $tpl = APPROOT."mod/{$this->controller->request->module}/tpls/sec_{$section_name}.php";
        if(!file_exists($tpl))
            $tpl = APPROOT."tpls/sec_{$section_name}.php";
        //check if the main section exists
        if(!file_exists($tpl))
            return;

        $section_function = "section_$section_name";
        //run the section logic
        if(method_exists($this->controller->module,$section_function)){
            $data = $this->controller->module->$section_function();
        }
        $data = (is_array($data))?$data:array();
    
        extract($data);

        include_once($tpl);
    }

    function getTemplatePath()
    {
        if(!isset($this->template))
            $this->loadDefaultTemplate();
        
        $controller = shnFrontController::getController();
        $mod = $controller->request->module;
        //check the module path 
        $tpl = APPROOT."mod/$mod/tpls/{$this->template}.php";
        if(file_exists($tpl))
            return $tpl;
        //check the main templates 
        $tpl = APPROOT."tpls/{$this->template}.php";
        if(file_exists($tpl))
            return $tpl;
    
        throw new shnViewNotFound();//@todo load default template
    }

    function loadDefaultTemplate()
    {
        $controller = shnFrontController::getController();
        $action = $controller->action;
        $this->template = 'act_'.$action;
    }
}
