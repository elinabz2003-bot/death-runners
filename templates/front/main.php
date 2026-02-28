<?php
/**
 * Affichage du contenu principal de la page.
 *
 * Ce script affiche la variable $main, qui contient le contenu HTML principal
 * capturé préalablement avec ob_start() et ob_get_clean() dans un autre script.
 * Il est généralement inclus dans un template (ex: main.php).
 *
 * @var string $main Contenu HTML principal à afficher
 */


if (isset($main)) {
    echo $main;
}
?>
