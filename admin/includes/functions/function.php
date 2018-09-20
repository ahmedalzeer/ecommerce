<?php
    /* page title function */

        function getTitle()
        {
            global $pagetitle;
            if (isset($pagetitle))
            {
                echo $pagetitle;
            }
            else
            {
                echo 'shop';
            }
        }

    /* redirect function when error */

        function redirecthome($themsg , $url = null, $second = 3)
                {
                    if($url === null)
                    {
                        $url = 'index1.php';
                        $link = 'homepage';
                    }
                    else
                        {
                            if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '')
                            {
                                $url = $_SERVER['HTTP_REFERER'];
                                $link = 'back';
                            }
                            else
                            {
                                $url = 'index1.php';
                                $link = 'homepage';
                            }
                        }
                    echo '<div class="container">';
                    echo $themsg;
                    echo "<div class='alert alert-info'>you will redirect to $link after ".$second." seconds</div>";
                    echo '</div>';
                    header("refresh:".$second."; url=$url");
                    exit();
                }

    /* function to check if item exist in database or not */

        function checkitem($select,$from,$value)
        {
            global $con;
            $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
            $statement->execute([$value]);
            $row = $statement->rowCount();
            return $row;
        }

    /* function to get count of data from database */

        function countitem($colmun,$table)
        {
            global $con;

            $stmt2 = $con->prepare("SELECT COUNT($colmun) FROM $table");
            $stmt2->execute();
            return $stmt2->fetchColumn();

        }

    /* function to get latest items */

        function getlatest($select,$table,$order,$limit = 5)
        {
            global $con;

            $getstmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
            $getstmt->execute();
            return $getstmt->fetchAll();

        }

?>