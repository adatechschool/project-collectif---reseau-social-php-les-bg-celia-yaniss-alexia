<?php

    $head ='<head>
                <meta charset="utf-8">
                <title>ReSoC - Actualités</title> 
                <meta name="author" content="Julien Falconnet">
                <link rel="stylesheet" href="style.css"/>
            </head>';

    $header ='  <header>
                    <a href="admin.php">
                        <img src="tortue.png" alt="Logo de notre réseau social"/>
                    </a>
                    <nav id="menu">
                        <a href="msgpost.php">Publier</a>
                        <a href="news.php">Actualités</a>
                        <a href="wall.php?user_id='.$_SESSION["connected_id"].'">Mur</a>
                        <a href="feed.php?user_id='.$_SESSION["connected_id"].'">Flux</a>
                        <a href="tags.php?tag_id='.$_SESSION["connected_id"].'">Mots-clés</a>
                    </nav>
                    <nav id="user">
                        <a href="#">▾ Profil</a>
                        <ul>
                            <li><a href="settings.php?user_id='.$_SESSION["connected_id"].'">Paramètres</a></li>
                            <li><a href="followers.php?user_id='.$_SESSION["connected_id"].'">Mes suiveurs</a></li>
                            <li><a href="subscriptions.php?user_id='.$_SESSION["connected_id"].'">Mes abonnements</a></li>
                            <li><a href="login.php">Se connecter</a></li>
                        </ul>
                    </nav>
                </header>';

                function article($post)
                {
                    return "<article>
                    <h3>
                        <time>".$post['created']."</time>
                    </h3>
                    <address><a id='author' href='wall.php?user_id=".$post['user_id']."'>".$post['author_name']."</a></address>
                    <div>
                        <p>".$post['content']."</p>
                    </div>                                            
                    <footer>
                        <small> ♥ ".$post['like_number']."</small>
                        <a href=''>".$post['taglist']."</a>
                    </footer>
                </article>
";
                }
?>