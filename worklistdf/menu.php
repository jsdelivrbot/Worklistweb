<script>
    $(document).ready(function () {
        $("#usuario").click(function () {

        })
    })
</script>


<style>
    input[type="file"] {
        display: none;
    }

    input[type="submit"] {
        display: none;
    }

    #form-uploud{
        width: 60%;
        margin-left: 20%;
        margin-right: 20%;
        text-align: center;
    }

    .custom-file-upload {
        border: transparent;
        display: inline-block;
        cursor: pointer;
    }
    .texto-mudarsenha{
        color: #85e2ff;
        text-decoration: none;
    }
    .texto-mudarsenha:hover{
        color: #bbefff;
        text-decoration: none;
    }
</style>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top " id="mainNav">
    <a href="index.php" class="navbar-brand">
        <span class="img-logo">Goías Saúde</span>
    </a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">

            <li class="nav-item li-login" data-toggle="tooltip" data-placement="right" title="Metas" >
                <form id ="form-uploud"  method="post" enctype="multipart/form-data" action="uploader/recebeUpload.php">
                    <label class="custom-file-upload">
                        <input id="selecionarimagem" name="arquivo" type="file" onChange="this.form.submit()" />
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
                            <a class="d-block small texto-mudarsenha" href="mudar-senha.php">Mudar Senha</a>
                        </a>
                    </label>
                </form>
            </li>

            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                <a class="nav-link" href="index.php">
                    <i class="fa fa-fw fa-home"></i>
                    <span class="nav-link-text">Campanhas</span>
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
            
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="cmv">
                <a class="nav-link" href="cmv.php">
                    <i class="fa fa-fw fa-shield"></i>
                    <span class="nav-link-text">CMV</span>
                </a>
            </li>

            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Metas">
                <a class="nav-link" href="mix.php">
                    <i class="fa fa-fw fa-sort-alpha-asc"></i>
                    <span class="nav-link-text">Mix de Produtos</span>
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
           <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Metas">
               <a class="nav-link" href="atualizar.php" >
                    <i class="fa fa-fw fa-refresh"></i>
                    <span class="nav-link-text">Atualizar Sistema</span>
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




