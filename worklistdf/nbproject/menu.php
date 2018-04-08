<script>
    $(document).ready(function(){
        $("#usuario").click(function(){
            
        })
    })
</script>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top " id="mainNav">
    <a href="index.php" class="navbar-brand">
        <span class="img-logo">DF Distribuidora - 30 Anos</span>
    </a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">

            <li class="nav-item li-login" data-toggle="tooltip" data-placement="right" title="Metas">
                <a class="nav-link" >
                    <?php
                    $url = "img/" . $_SESSION['idrepresentante'] . ".jpg";
                    if (file_exists($url)) {
                        ?>
                        <img class="profile-img-card" id="usuario" src="img/<?php echo $_SESSION['idrepresentante']; ?>.jpg">
                        <?php } else {
                        ?>
                        <img class="profile-img-card" id="usuario" src="img/semimagem.png">  
                        <?php
                    }
                    ?>           
                    <span class="nav-link-text texto-login"><strong><?php echo $_SESSION['usuario'] ?></strong></span>
                    <span class="nav-link-text msg-login">Seja Bem Vindo ao WorklistWEB.</span>
                </a>
            </li>

            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                <a class="nav-link" href="index.php">
                    <i class="fa fa-fw fa-home"></i>
                    <span class="nav-link-text">Início</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Metas">
                <a class="nav-link" href="monitoramento.php">
                    <i class="fa fa-fw fa-handshake-o"></i>
                    <span class="nav-link-text">Monitoramento</span>
                </a>
            </li>

            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Metas">
                <a class="nav-link" href="potencial.php">
                    <i class="fa fa-fw fa-trophy"></i>
                    <span class="nav-link-text">Potenciais</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Metas">
                <a class="nav-link" href="inadimplencia.php">
                    <i class="fa fa-money"></i>
                    <span class="nav-link-text">Inadimplência</span>
                </a>
            </li>

            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Metas">
                <a class="nav-link" href="historicodocliente.php">
                    <i class="fa fa-fw fa-handshake-o"></i>
                    <span class="nav-link-text">Histórico do Cliente</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Metas">
                <a class="nav-link" href="visaoegarra.php" >
                    <i><img src="img/visaoegarra.svg" width="25px"></i>
                    <span class="nav-link-text">Visão e Garra</span>
                </a>
            </li>


        </ul>
        <ul class="navbar-nav sidenav-toggler">
            <li class="nav-item">
                <a class="nav-link text-center" id="sidenavToggler">
                    <i class="fa fa-fw fa-angle-left"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">


            <li class="nav-item">
                <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-fw fa-sign-out"></i>Sair</a>
            </li>
        </ul>
    </div>
</nav>




