<?php 
/**
 * Micro thesauri data object will manage all the microthesauri related
 * data manupilation
 *
 * This file is part of OpenEvsys.
 *
 * Copyright (C) 2009  Human Rights Information and Documentation Systems,
 *                     HURIDOCS), http://www.huridocs.org/, info@huridocs.org
 *
 * OpenEvsys is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OpenEvsys is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @auther  H J P Fonseka <jo@respere.com>
 * 
 * @package	OpenEvsys
 * @subpackage	DataModel
 * 
 */

class MicroThesauri
{

    private $list_code = null;

    public function __construct($list_code)
    {
        $this->list_code = (int) $list_code;
    }

    /** 
     * return elements of select.
     */
    public function getTerms()
    {
        global $global;
        global $conf;
        $sql = "SELECT m.vocab_number,  IFNULL(l.msgstr , m.english) as 'label' ,m.term_level,m.term_order ,m.parent_vocab_number 
                    FROM mt_vocab AS m
                    LEFT JOIN mt_vocab_l10n l ON ( l.msgid = m.vocab_number AND l.locale = '{$conf['locale']}' )
                    WHERE m.list_code = '{$this->list_code}' AND m.visible='y'";
        $terms = $global['db']->GetAll($sql);
        return $terms;
    }






    public function getListCode()
    {
        return $list_code;
    }
}
