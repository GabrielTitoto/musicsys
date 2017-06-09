<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">MusicSys</a>
    </div>

    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i>
                <?php echo $_SESSION['usuario']['nome'] ?>
                <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li>
                    <a href="usuarios_visualizar.php?id=<?php echo $_SESSION['usuario']['id'] ?>"><i class="fa fa-user fa-fw"></i> Visualizar Perfil</a>
                </li>
                <li>
                    <a href="usuarios_editar.php?id=<?php echo $_SESSION['usuario']['id'] ?>"><i class="fa fa-user fa-fw"></i> Editar Perfil</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="login.php"><i class="fa fa-sign-out fa-fw"></i> Sair</a>
                </li>
            </ul>
        </li>
    </ul>
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse collapse">
            <ul class="nav" id="side-menu">
                <?php if ($_SESSION["usuario"]["admin"]) { ?>
                    <li>
                        <a href="./usuarios_listar.php"><i class="fa fa-user fa-fw"></i> Gerenciar alunos</a>
                    </li>
                    <li>
                        <a href="./cursos_listar.php"><i class="fa fa-music fa-fw"></i> Cadastrar cursos</a>
                    </li>
                    <li>
                        <a href="./presencas_listar.php"><i class="fa fa-check-circle-o fa-fw"></i> Lista presença</a>
                    </li>
                <?php } ?>
                <li>
                    <a href="./materiais_listar.php"><i class="fa fa-file-o fa-fw"></i> Materiais</a>
                </li>
                <li>
                    <a href="./horarios_listar.php"><i class="fa fa-clock-o fa-fw"></i> Horários</a>
                </li>
                <li>
                    <a href="./pagamentos_listar.php"><i class="fa fa-credit-card fa-fw"></i> Pagamentos</a>
                </li>
            </ul>
        </div>
    </div>
</nav>