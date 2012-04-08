<?php
/**
 * Request class will be used to process the request
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


class shnRequest
{
    public static $request;

    public 
    $module = NULL,
    $action = NULL,
    $view   = NULL,
    $stream = NULL;

    static public function getRequest()
    {
        if (!isset(self::$request)) {
            self::$request = new shnRequest();
        }

        return self::$request;
    }

    public function __construct()
    { 
        //only use GET values to adhere to REST concepts
        $this->module = (isset($_GET['mod']))?$_GET['mod']:'home';
        $this->action = (isset($_GET['act']))?$_GET['act']:'default';
        $this->view = (isset($_GET['stream']))?$_GET['stream']:'html';
        $this->tpl = (isset($_GET['view']))?$_GET['view']:NULL;
    }

    public function getRequestModule()
    {
        return $this->module;
    } 
    
    public function getRequestAction()
    {
        return $this->action;
    } 
} 
