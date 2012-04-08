<?php
/**
 * shnView the parent view class where other views should extend. 
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
 
abstract class shnView
{
    protected $data;
    /**
     * set_data this will set the model data to the view 
     * 
     * @param mixed $data 
     * @access public
     * @return void
     */
    public function setData($data)
    {
       $this->data = (is_array($data))?$data:array();
    }

    /**
     * render() this should be implemented from the sub views to render 
     * content
     * 
     * @abstract
     * @access public
     * @return void
     */
    abstract public function render();

    
    public function setTemplate($template)
    {
        //for security strip all the slashes
        if(isset($template)){
            $template = str_replace("/",'',$template);
            $this->template = $template;
        }
    }
}
