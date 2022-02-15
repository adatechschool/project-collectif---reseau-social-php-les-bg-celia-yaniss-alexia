<!doctype html>
<html lang="fr">
<?php
    include('refactoring.php');
    echo $head;
?>
    <body>
        <?php
            echo $header;
        ?>
        <div id="wrapper">
            <aside>
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez les derniers messages de
                        tous les utilisatrices du site.</p>
                </section>
            </aside>
            <main>             
                <?php

                    $mysqli = new mysqli("localhost", "root", "", "socialnetwork");

                    if ($mysqli->connect_errno)
                    {
                        echo "<article>";
                        echo("Échec de la connexion : " . $mysqli->connect_error);
                        echo("<p>Indice: Vérifiez les parametres de <code>new mysqli(...</code></p>");
                        echo "</article>";
                        exit();
                    }

                    $laQuestionEnSql = "
                        SELECT posts.content,
                        posts.created,
                        posts.user_id,
                        users.alias as author_name,  
                        count(likes.id) as like_number,  
                        GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                        FROM posts
                        JOIN users ON  users.id=posts.user_id
                        LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                        LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                        LEFT JOIN likes      ON likes.post_id  = posts.id 
                        GROUP BY posts.id
                        ORDER BY posts.created DESC  
                        LIMIT 5
                        ";

                    $lesInformations = $mysqli->query($laQuestionEnSql);

                    if ( ! $lesInformations)
                    {
                        echo "<article>";
                        echo("Échec de la requete : " . $mysqli->error);
                        echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                        exit();
                    }

                    while ($post = $lesInformations->fetch_assoc())
                        {
                            echo article($post);
                        }
                ?>
            </main>
        </div>
    </body>
</html>
