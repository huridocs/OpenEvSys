<?php
/**
 * Paging class
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

class shnPager implements BrowseStrategy
{
    protected $query;
    protected $rpp;
    protected $show_all = false;
    protected $request_page = 1;
    protected $res = null;
    protected $last_page ;
    protected $ppp = 10;

    function __construct($query=null)
    { 
        global $conf;
        $this->rpp = (isset($conf['paging_rpp']))?$conf['paging_rpp']:10;
        $this->rpp = (isset($_SESSION['rpp']))?$_SESSION['rpp']:$this->rpp;
        //to keep the backword compatibility
        if(isset($query)){
            $this->query = $query;
            $this->process_request();
            $this->fetch_data();
            $this->set_page_numbers();
            return $this;
        }
    }

    public function ExecuteQuery($query)
    {
        global $conf;
        $this->query = $query;
        $this->process_request();
        $this->fetch_data();
        $this->set_page_numbers();
        return $this;
    }

    protected function process_request()
    {
        $this->rpp = (isset($_REQUEST['rpp'])&& (int)$_REQUEST['rpp']>0)? (int)$_REQUEST['rpp']:$this->rpp;
        $_SESSION['rpp'] = $this->rpp;
        if($_REQUEST['request_page'] =='all' || $_GET['show_all']){
            $this->show_all = true;
        }
        
        $this->request_page = (int) $_REQUEST['request_page'];
        $current_page = (int) $_REQUEST['current_page'];
        if(isset($_REQUEST['next_page']))
            $this->request_page = $current_page + 1;
        if(isset($_REQUEST['previous_page']))
            $this->request_page = $current_page - 1;
        if($this->request_page == 0 )
            $this->request_page = 1;
    }

    protected function fetch_data()
    {
        global $global;
        define('ADODB_FETCH_ASSOC',2);
        define('ADODB_FETCH_BOTH',3);
        $fetchmode=$global['db']->SetFetchMode(ADODB_FETCH_ASSOC);
        if($this->show_all){
            $this->res=$global['db']->Execute($this->query);	
        }else{
            $this->res=$global['db']->PageExecute($this->query, $this->rpp, $this->request_page);
            $this->request_page = $this->res->AbsolutePage();
        }

        //revert the fetch mode
        $global['db']->SetFetchMode($fetchmode);
    }

    protected function set_page_numbers()
    {
        if(isset($this->res)){
            $this->last_page = $this->res->LastPageNo();
        }
    } 

    public function get_page_data()
    {
        return $this->res;
    }




    /**
     * render_pages will render page links in html 
     * 
     * @access public
     * @return void
     */
    public function render_pages($id = null)
    {
        $request = shnRequest::getRequest();
        $args = $_GET;
        unset($args['mod']);
        unset($args['act']);
?>
<div class="row" style="margin-left:0px;">
        <?php if($this->show_all){ ?>
     <div class="pages pagination <?php echo $id ?>" style="margin:0px;padding-top: 0px;width:auto">
            <ul>
       
                <li><a href="<?php $args['request_page'] = 1 ; get_url($request->module, $request->action , $request->tpl , $args )?>" ><?php echo _t('PAGINATE_RESULTS') ?></a></li>
        </ul>
        </div>
            <?php }else{ ?>
    <div class="pages pagination span <?php echo $id ?>" style="margin:0px;padding-top: 0px;width:auto">
            <ul>
     
            <?php if($this->request_page != 1){ ?>
                <li><a href="<?php $args['request_page'] = $this->request_page - 1;  get_url($request->module, $request->action , $request->tpl , $args)?>" ><?php echo _t('BACK') ?></a></li>
            <?php } ?>
            <?php
            
                //paging logic
                $start = $this->request_page - 5;
                $end   = $this->request_page + 5;
                
                if($this->request_page < 6){ $end = 5 - $this->request_page + $end; $start = 1;}
                if($this->last_page < $end){ $start = $start - ($end - $this->last_page) ;$end = $this->last_page;}
                if($start < 1) $start = 1;
            ?>
            <?php if($start > 1){ ?>
                <li><a href="<?php $args['request_page'] = $start - 1; get_url($request->module, $request->action , $request->tpl , $args )?>" ><?php echo '&lt;&lt;' ?></a></li>
            <?php } ?>
            <?php for($i = $start; $i<=$end; $i++ ){ ?>
                <?php if($i == $this->request_page){ ?> 
                     <li class="active"><a href="#"><?php echo $i ?></a></li>
                <?php     continue;
                      } ?>
                <li><a href="<?php $args['request_page'] = $i ; get_url($request->module, $request->action , $request->tpl , $args )?>" ><?php echo $i ?></a>
            <?php } ?>
            <?php if($end < $this->last_page){ ?>
               <li> <a href="<?php $args['request_page'] = $end + 1; get_url($request->module, $request->action , $request->tpl , $args )?>" ><?php echo '&gt;&gt;' ?></a></li>
            <?php } ?>
            <?php if($this->request_page != $this->last_page){ ?>
                <li><a href="<?php $args['request_page'] = $this->request_page + 1; get_url($request->module, $request->action , $request->tpl , $args )?>" ><?php echo _t('NEXT') ?></a></li>
            <?php } ?>
                <li><a href="<?php $args['request_page'] = 'all'; get_url($request->module, $request->action , $request->tpl , $args )?>" ><?php echo _t('SHOW_ALL') ?></a></li>
                </ul>
        </div>
                <div class="input-prepend input-append span" style="width:auto">
                    <span class="add-on"><?php echo _t('RECORDS_PER_PAGE') ?></span>
                
                <input style="width:22px;" type='text' id="rpp" name="rpp" size='3' value="<?php echo $this->rpp ?>"
                       onchange="/*$('#rpp_set').attr('href',$('#rpp_set').attr('href')+ '&rpp=' + $(this).val());*/"/>
                <span class="add-on"><a id="rpp_set" href="<?php $args['request_page'] = 1;
                
                get_url($request->module, $request->action , $request->tpl , $args );
                $args2 = $args;
                unset($args2['rpp']);
                ?>" onclick="$(this).attr('href', '<?php get_url($request->module, $request->action , $request->tpl , $args2 )?>'+ '&rpp=' + $('#rpp').val())" ><?php echo _t('SET') ?></a>
                </span>
                </div>
    <div class="span well well-small" style="padding: 4px;width:auto">
               <?php echo _t('PAGE__')." ".$this->request_page.' / '.$this->last_page ?>
                
    </div>
        <?php } ?>
            
        </div>
<?php
    }


    /**
     * render_pages will render page links in html 
     * 
     * @access public
     * @return void
     */
    public function render_post_pages()
    {
        $this->render_pages('post_pages');
        static $set = true;
        if($set){
?>
        <script language="javascript">
        $(document).ready(function(){
            $('.post_pages').find('a').click(function() { 
                var $form = $(this).parents().filter('form');
                var $link =$(this).attr('href');
                $form.attr('action',$link);
                $form.submit();
                return false;
            });
        });
        </script>
<?php
        }
        $set = false;
    }
}
