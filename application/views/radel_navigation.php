    <!-- Fixed navbar -->
    <?php
    require('translations.php');
    $query = $this->db->query('select * from `teilnehmer` where concat(`Vorname`," ",`Name`) = "'.$user.'"');
    $registerd = $query->num_rows();
    ?>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">WRAM</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="<?= $active_1 ?>"><a href="main/einstellung"><i class="fa fa-cog"></i> <?php
                if($registerd) {
                  $strings['settings'][$lang];
                } else {
                  $strings['register'][$lang];
                }
                ?></a></li>
            <?php
              if($registerd) {
                ?>
                <li class="<?= $active_2 ?>"><a href="main/eintragen"><i
                        class="fa fa-bicycle"></i> <?= $strings['km'][$lang]; ?></a></li>
                <li class="dropdown <?= $active_3 ?>">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i
                        class="fa fa-bar-chart"></i> <?= $strings['stats'][$lang]; ?> <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="main/charts/ich"><?= $strings['ich'][$lang]; ?></a></li>
                    <li><a href="main/charts/team"><?= $strings['team'][$lang]; ?></a></li>
                    <li><a href="main/charts/dep"><?= $strings['dep'][$lang]; ?></a></li>
                    <li><a href="main/charts/firma"><?= $strings['firma'][$lang]; ?></a></li>
                  </ul>
                </li>
                <?php
              }
            ?>
            <li><a href="/"><?php
                $query = $this->db->get('teilnehmer');
                echo $query->num_rows();
                ?> <?= $strings['user'][$lang]; ?></a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
		  	<?= $admin ?>
            <li><a href="/"><?= $strings['greeting'][$lang]; ?>, <?= $user ?><span class="sr-only"></span></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
