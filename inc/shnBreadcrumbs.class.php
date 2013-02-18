<?php

class shnBreadcrumbs {

    private $crumbs = array();

    private function __construct() {
        
    }

    public static function getBreadcrumbs() {
        static $instance = null;
        if ($instance == null)
            $instance = new shnBreadcrumbs();
        return $instance;
    }

    public function pushCrumb($crumb, $level = null) {
        $level = (!isset($level)) ? count($this->crumbs) : $level;
        $this->crumbs[$level] = $crumb;
    }

    public function renderBreadcrumbs() {
        ksort($this->crumbs);
        if($this->crumbs){
        ?>
        <div>
            <ul class="breadcrumb">
        <?php
        $i = 1;
        foreach ($this->crumbs as $c) {
            if ($i != count($this->crumbs)) {
                ?>
                        <li>
                            <?php if ($i > 1) {
                                echo '<span class="divider">/</span>';
                            } ?>

                            <a href="<?php get_url($c['mod'], $c['act'], $c['view'], $c['args'], $c['stream']) ?>" ><?php echo $c['name'] ?></a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li class="active">
                           <?php if ($i > 1) {
                                echo '<span class="divider">/</span>';
                            } ?>
                        <?php echo $c['name'] ?>
                        </li>
                        <?php
                    }
                    $i++;
                }
                ?>
            </ul>
        </div>
        <?php
        }
    }

}

