<?php
/**
 * shnModule is the main module class and all the modules should extend form 
 * this.
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

class shnModule
{

    function section_menu()
    {
        $data['module'] = $_GET['mod'];
        $data['breadcrumbs'] = shnBreadcrumbs::getBreadcrumbs();
        return $data;
    }

    function section_mod_menu()
    {
        $data['breadcrumbs'] = shnBreadcrumbs::getBreadcrumbs();
        return $data;
    }

    function section_breadcrumb()
    {
        $data['breadcrumbs'] = shnBreadcrumbs::getBreadcrumbs();
        return $data;
    }

    function act_404_error(){ $this->title = _t('404_ERROR___PAGE_NOT_FOUND'); change_tpl('act_error');}
    function act_db_error(){ $this->title = _t('DATABASE_ERROR'); }
    function act_access_deny(){ }
    function act_unknown_error(){ $this->title = _t('SYSTEM_ERROR'); change_tpl('act_error');}
}
