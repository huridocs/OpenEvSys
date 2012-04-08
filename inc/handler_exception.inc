<?php
/**
 * This is the default exception handler of the framework.
 * It will handle all the uncaught exceptions and generate 
 * an error page for users. 
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


function exception_handler($e)
{
    //if the 404 exception is thrown print out the error page
    ob_clean();
    if($e instanceof shn404Exception){
        header("HTTP/1.0 404 Not Found");
        shnFrontController::loadDefaultModule();
        shnFrontController::loadDefaultView();
        $controller = shnFrontController::getController();
        $controller->setAction('404_error');
        $controller->sendResponce();
    }

    else if($e instanceof shnAccessDeny){
        shnFrontController::loadDefaultModule();
        shnFrontController::loadDefaultView();
        $controller = shnFrontController::getController();
        $controller->setAction('access_deny');
        $controller->sendResponce();
    }
    else if($e instanceof ADODB_Exception){
        $controller = shnFrontController::getController();
        $controller->setAction('db_error');
        $controller->sendResponce();
    }
    else{
        $controller = shnFrontController::getController();
        $controller->setAction('unknown_error');
        $controller->sendResponce();
    }
}

set_exception_handler('exception_handler');





class shnException extends Exception
{
}




/**
 * shn404Exception 
 * 
 * @uses shnException
 * @package exception 
 */
class shn404Exception extends shnException
{

}

/**
 * shnAccessDeny 
 * 
 * @uses shnException
 * @package exception 
 */
class shnAccessDeny extends shnException
{

}

class shnViewNotFound extends shnException
{

}

class Invalide_Parameter_Exception extends Exception
{
    //define sahana specific elements
}

