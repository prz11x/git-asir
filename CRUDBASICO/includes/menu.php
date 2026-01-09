<nav>
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" href="index.php">Home</a>
        </li>

        <?php

        if($_SESSION['permiso']>1){
        echo '<li class="nav-item">
            <a class="nav-link" href="listarFiguras.php">Gestionar Figuras</a>
        </li>';
        }else{
            echo '<li class="nav-item">
            <a class="nav-link" href="verFiguras.php">Ver Figuras</a>
        </li>';
        }

        ?>

        <li class="nav-item">
            <a class="nav-link" href="logout.php">Salir</a>
        </li>
    </ul>
</nav>