<?php


class shnBreadcrumbs
{
    private $crumbs = array();

    private function __construct(){ }

    public static function getBreadcrumbs()
    {
      static $instance = null;
      if ( $instance == null )
        $instance = new shnBreadcrumbs();
      return $instance;
    }

    public function pushCrumb($crumb,$level = null)
    {
        $level = (!isset($level))? count($this->crumbs) : $level ;
        $this->crumbs[$level] = $crumb;
    }

    public function renderBreadcrumbs()
    {
        ksort($this->crumbs);
        ?>
        <div id="breadcrumb">
        <?php foreach($this->crumbs as $c){ ?>
            <a href="<?php get_url($c['mod'],$c['act'],$c['view'],$c['args'],$c['stream'])?>" ><?php echo $c['name'] ?></a><span>&nbsp;&gt;&nbsp;</span>
        <?php } ?>
        <br />
        </div>
        <?php
    }
}


