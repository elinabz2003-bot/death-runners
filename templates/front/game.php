<?php
/**
 * Script principal du jeu "Death Runners: Code of Shadows"
 *
 * Ce fichier gère l'affichage dynamique du jeu selon le niveau en cours.
 * Il contient :
 *
 * 1. Vérification que l'utilisateur est connecté.
 * 2. Initialisation du niveau s'il n'existe pas.
 * 3. Affichage du contenu HTML correspondant à chaque niveau du jeu.
 * 4. Code JavaScript pour la logique client (timer, progression, skull, requêtes AJAX).
 * 5. Inclusion du squelette (header, main, footer).
 *
 * Niveaux disponibles :
 * - Niveau 1 : reconnaissance de code (Python vs Java vs C)
 * - Niveau 2 : compléter un code Python
 * - Niveau 3 : détection d'une erreur dans un code
 * - Niveau 4 : dilemme moral
 * - Au-delà : message de fin de jeu
 */


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($racine_path)) {
    $racine_path = '../../';
}


if (!isset($_SESSION['user_id'])) {
    header("Location: " . $racine_path . "templates/front/login.php");
    exit;
}


if (!isset($_SESSION['level'])) {
    $_SESSION['level'] = 1;
}
$currentLevel = $_SESSION['level'];


ob_start();
?>

<!-- Conteneur caché pour la tête de mort qui rigole -->
<div id="skullModal" class="skull-modal">
    <!-- On utilise $racine_path pour trouver l'image et l'audio -->
    <img src="<?= $racine_path ?>templates/front/tetemort.jpg" alt="Skull" id="skullImg">
    <audio id="skullAudio" src="<?= $racine_path ?>templates/front/rire.mp3"></audio>
</div>

<section class="game">
  <div class="game-container" id="gameContainer">
    <!-- En-tête général -->
    <div class="game-header">
      <h1>Death Runners: Code of Shadows</h1>
      <div class="level">Niveau : <span id="levelDisplay"><?= $currentLevel ?></span></div>
      <div class="timer">Temps restant : <span id="timer">60</span>s</div>
    </div>

    <?php if ($currentLevel == 1): ?>
      <!-- Niveau 1 : Reconnaissance de code Python -->
      <div class="level1">
        <h2>Quel code est écrit en Python ?</h2>
        
        <div class="option-container" data-answer="1">
          <pre>
public class Hello {
    public static void main(String[] args) {
        System.out.println("Bonjour !");
    }
}
          </pre>
          <p>Option 1 (Java)</p>
        </div>

        <div class="option-container" data-answer="2">
          <pre>
def greet():
    print("Bonjour !")

greet()
          </pre>
          <p>Option 2 (Python)</p>
        </div>

        <div class="option-container" data-answer="3">
          <pre>
#include &lt;stdio.h&gt;
int main() {
    printf("Bonjour !");
    return 0;
}
          </pre>
          <p>Option 3 (C)</p>
        </div>
      </div>

    <?php elseif ($currentLevel == 2): ?>
      <!-- Niveau 2 : Compléter un code inachevé -->
      <div class="level2">
        <h2>Complétez le code suivant pour afficher les nombres de 1 à 10 :</h2>
        <pre>
for i in range(___):
    print(i)
        </pre>
        <input type="text" id="level2Answer" placeholder="Exemple: 1,11">
        <button id="submitLevel2" class="btn">Valider</button>
        <p id="level2Feedback"></p>
      </div>

    <?php elseif ($currentLevel == 3): ?>
      <!-- Niveau 3 : Trouver l'erreur dans le code -->
      <div class="level3">
        <h2>Identifiez l'erreur dans ce code Python :</h2>
        <pre>
def additionner(a, b):
    return a + b

print(additionner(5))
        </pre>
        <input type="text" id="level3Answer" placeholder="Expliquez l'erreur">
        <button id="submitLevel3" class="btn">Valider</button>
        <p id="level3Feedback"></p>
      </div>

    <?php elseif ($currentLevel == 4): ?>
      <!-- Niveau 4 : Dilemme Moral -->
      <div class="level4">
        <h2>Si tu avais le choix, tu préfères :</h2>
        <p>1️⃣ Être celui qui fait du mal 😈</p>
        <p>2️⃣ Être celui à qui on fait du mal 😢</p>
        <div class="moral-options">
          <button class="moral-option" data-answer="1">Option 1</button>
          <button class="moral-option" data-answer="2">Option 2</button>
        </div>
        <p id="level4Feedback"></p>
      </div>

    <?php else: ?>
      <!-- Fin du jeu -->
      <div class="game-end">
        <h2>Félicitations, vous avez terminé le jeu !</h2>
      </div>
    <?php endif; ?>
  </div>
</section>

<script>



let timeLeft = 60;
let acceleration = 1;
const timerElement = document.getElementById("timer");
const gameContainer = document.getElementById("gameContainer");

let timerInterval = setInterval(() => {
    timeLeft -= acceleration;
    if (timeLeft <= 0) {
        timeLeft = 0;
        clearInterval(timerInterval);
        alert("Temps écoulé ! GAME OVER.");
        location.reload();
    }
    timerElement.textContent = Math.floor(timeLeft);
    if (timeLeft <= 15) {
        gameContainer.classList.add("flash-red");
    } else {
        gameContainer.classList.remove("flash-red");
    }
}, 1000);




function showSkull() {
    const skullModal = document.getElementById('skullModal');
    const skullAudio = document.getElementById('skullAudio');
    skullModal.style.display = 'block';
    skullAudio.play();
    setTimeout(() => {
        skullModal.style.display = 'none';
    }, 5000);
}




function nextLevel() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "<?= $racine_path ?>control/game_control.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function(){
    location.reload();
  };
  xhr.send("action=next_level");
}




<?php if ($currentLevel == 1): ?>
document.querySelectorAll('.option-container').forEach(option => {
  option.addEventListener('click', function() {
    document.querySelectorAll('.option-container').forEach(o => o.classList.remove('selected'));
    this.classList.add('selected');
    let selected = this.getAttribute('data-answer');
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "<?= $racine_path ?>control/game_control.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
      let response = JSON.parse(this.responseText);
      if (response.success) {
        alert("Bonne réponse !");
        nextLevel();
      } else {
        alert("Mauvaise réponse, le temps s'accélère !");
        showSkull();
        acceleration += 0.5;
      }
    };
    xhr.send("action=validate_level1&answer=" + encodeURIComponent(selected));
  });
});
<?php elseif ($currentLevel == 2): ?>
document.getElementById("submitLevel2").addEventListener("click", function(){
  let answer = document.getElementById("level2Answer").value.trim();
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "<?= $racine_path ?>control/game_control.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function(){
    let response = JSON.parse(this.responseText);
    let feedback = document.getElementById("level2Feedback");
    if(response.success){
      feedback.textContent = "Correct ! Passage au niveau suivant.";
      feedback.style.color = "#00ffcc";
      setTimeout(nextLevel, 2000);
    } else {
      feedback.textContent = "Réponse incorrecte. Le temps s'accélère !";
      feedback.style.color = "#ff3333";
      showSkull();
      acceleration += 0.5;
    }
  };
  xhr.send("action=validate_level2&answer=" + encodeURIComponent(answer));
});
<?php elseif ($currentLevel == 3): ?>
document.getElementById("submitLevel3").addEventListener("click", function(){
  let answer = document.getElementById("level3Answer").value.trim();
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "<?= $racine_path ?>control/game_control.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function(){
    let response = JSON.parse(this.responseText);
    let feedback = document.getElementById("level3Feedback");
    if(response.success){
      feedback.textContent = "Correct ! Passage au niveau suivant.";
      feedback.style.color = "#00ffcc";
      setTimeout(nextLevel, 2000);
    } else {
      feedback.textContent = "Réponse incorrecte. Le temps s'accélère !";
      feedback.style.color = "#ff3333";
      showSkull();
      acceleration += 0.5;
    }
  };
  xhr.send("action=validate_level3&answer=" + encodeURIComponent(answer));
});
<?php elseif ($currentLevel == 4): ?>
document.querySelectorAll('.moral-option').forEach(button => {
  button.addEventListener('click', function(){
    let selected = this.getAttribute('data-answer');
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "<?= $racine_path ?>control/game_control.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function(){
      let response = JSON.parse(this.responseText);
      let feedback = document.getElementById("level4Feedback");
      if(response.success){
        feedback.textContent = response.message;
        feedback.style.color = "#00ffcc";
      } else {
        feedback.textContent = "Erreur lors du traitement de votre réponse.";
        feedback.style.color = "#ff3333";
        showSkull();
      }
    };
    xhr.send("action=validate_level4&answer=" + encodeURIComponent(selected));
  });
});
<?php endif; ?>
</script>

<?php

$main = ob_get_clean();

$titre = 'Jeu';


include($racine_path . 'templates/front/header.php');
include($racine_path . 'templates/front/main.php');
include($racine_path . 'templates/front/footer.php');
