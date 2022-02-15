<?php
session_start();
?>
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
            <?php
            /** AFFICHER LES POST SUR LE MUR
             * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php*/
            $userId =intval($_GET['user_id']);
            ?>
            <?php
            /*Etape 2: se connecter à la base de donnée*/
            $mysqli = new mysqli("localhost", "root", "", "socialnetwork");
            ?>

            <aside>
                <?php
                /* Etape 3: récupérer le nom de l'utilisateur*/                
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                // echo "<pre>" . print_r($user, 1) . "</pre>";
                ?>
                <img src="neon.png" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les message de l'utilisatrice : <?php echo $user['alias'] ?>
                        (n° <?php echo $user['id'] ?>)
                    </p>
                </section>
            </aside>
            <main>
                <?php
                /*Etape 3: récupérer tous les messages de l'utilisatrice*/
                $laQuestionEnSql = "
                    SELECT posts.content, posts.user_id, posts.created, users.alias as author_name, 
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                $listAuteurs = [];
                $laQuestionEnSqlTwo = "SELECT * FROM users";
                $lesInformationsTwo = $mysqli->query($laQuestionEnSqlTwo);
                while ($user = $lesInformationsTwo->fetch_assoc())
                {
                    $listAuteurs[$user['id']] = $user['alias'];
                }
                // AFFICHER UNE FENETRE POUR ECRIRE UN POST
                    // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
                    $enCoursDeTraitement = isset($_POST['auteur']);
                    if ($enCoursDeTraitement)
                    {
                        // on ne fait ce qui suit que si un formulaire a été soumis.
                        // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                        // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                         echo "<pre>" . print_r($_POST, 1) . "</pre>";
                        // et complétez le code ci dessous en remplaçant les ???
                        $authorId = $_POST['auteur'];
                        $postContent = $_POST['message'];


                        //Etape 3 : Petite sécurité
                        // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                        $authorId = intval($mysqli->real_escape_string($authorId));
                        $postContent = $mysqli->real_escape_string($postContent);
                        //Etape 4 : construction de la requete
                        $lInstructionSqlTwo = "INSERT INTO posts "
                                . "(id, user_id, content, created) "
                                . "VALUES (NULL, "
                                . $authorId . ", "
                                . "'" . $postContent . "', "
                                . "NOW()".")";
                        echo $lInstructionSqlTwo;
                        // Etape 5 : execution
                        $ok = $mysqli->query($lInstructionSqlTwo);
                        if ( ! $ok)
                        {
                            echo "Impossible d'ajouter le message: " . $mysqli->error;
                        } else
                        {
                            echo "Message posté en tant que :" . $listAuteurs[$authorId];
                        }
                    }
                

                function foo($post){

                    /** AFFICHER LES POST SUR LE MUR
                     * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php*/
                    $userId =intval($_GET['user_id']);
                    $mysqli = new mysqli("localhost", "root", "", "socialnetwork");
                    
                    $laQuestionEnSql = "
                    SELECT posts.content, posts.user_id, posts.created, users.alias as author_name, 
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                    $lesInformations = $mysqli->query($laQuestionEnSql);
                    if ( ! $lesInformations)
                    {
                        echo("Échec de la requete : " . $mysqli->error);
                    };
                    $post = $lesInformations->fetch_assoc();
                    return '
                    <article>
                    <form action="wall.php?user_id='.$post['user_id'].'" method="post">
                        <input type="hidden" name="???" value="achanger">
                        <dl>
                            <dt><label for="auteur">Auteur</label></dt>
                            <dd><select name="auteur">
                                    foreach ('.$listAuteurs.' as '.$id.' => '.$alias.')
                                        echo "<option value="'.$id.'">'.$alias.'</option>";
                                </select></dd>
                            <dt><label for="message">Message</label></dt>
                            <dd><textarea name="message"></textarea></dd>
                        </dl>
                        <input type="submit">
                    </form> 
                </article>  
                    ';};
                while ($post = $lesInformations->fetch_assoc())
                {

                    // echo "<pre>" . print_r($post, 1) . "</pre>";
                    ?>                
                    <article>
                        <h3>
                            <time><?php echo $post['created'] ?></time>
                        </h3>
                        <address><a href="wall.php?user_id=<?php echo $post['user_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                        <div>
                            <p><?php echo $post['content'] ?></p>
                        </div>                                            
                        <footer>
                            <small>♥ <?php echo $post['like_number'] ?></small>
                            <a href=""><?php echo $post['taglist'] ?></a>,
                        </footer>
                    </article>

                <?php 
                } 
                ?> 
                <?php 
                echo foo($post);
                ?>  
            </main>
        </div>
    </body>
</html>
