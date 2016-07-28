    <!-- Fixed navbar -->
    <?php
    require_once('translations.php');
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
            <li class="<?= $active_1 ?>"><a href="main/einstellung"><i class="fa fa-cog"></i> <?= $strings['Einstellung'][$lang]; ?></a></li>
            <li class="<?= $active_2 ?>"><a href="main/eintragen"><i class="fa fa-bicycle"></i> Kilometer eintragen</a></li>
            <li class="dropdown <?= $active_3 ?>">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-bar-chart"></i> Statistiken <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="main/charts/ich">ich</a></li>
                <li><a href="main/charts/team">mein Team</a></li>
                <li><a href="main/charts/firma">meine Firma</a></li>
              </ul>
            </li>
            <li><a href="/"><?php
                $query = $this->db->get('teilnehmer');
                echo $query->num_rows();
                ?> Teilnehmer</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
		  	<?= $admin ?>
            <li><a href="/">Hallo, <?= $user ?><span class="sr-only"></span></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
