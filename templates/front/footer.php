</main>
    <footer>
        <div class="footer-wrapper">
            <div class="footer-logo">
                <h2>Death Runners</h2>
                <p>Embracing the Dark Future</p>
            </div>
            <div class="footer-menu">
                <ul>
                    <li><a href="<?= $baseUrl ?>terms">Conditions d'utilisation</a></li>
                    <li><a href="<?= $baseUrl ?>contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-social">
                <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date("Y") ?> Death Runners. All rights reserved.</p>
        </div>
    </footer>

<!-- Bandeau de consentement aux cookies -->
<div id="cookie-banner" class="cookie-banner">
    <div class="cookie-banner-content">
        <p>Death Runners utilise des cookies pour vous offrir une expérience de jeu optimale. En continuant, vous acceptez leur utilisation.</p>
        <button id="cookie-banner-accept">Accepter</button>
    </div>
</div>

<!-- Script de gestion du consentement aux cookies -->
<script>

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}


function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}


document.addEventListener("DOMContentLoaded", function() {
    if (!getCookie("cookiesConsent")) {
        document.getElementById("cookie-banner").style.display = "block";
    }
});


document.getElementById("cookie-banner-accept").addEventListener("click", function() {
    setCookie("cookiesConsent", "accepted", 30);
    document.getElementById("cookie-banner").style.display = "none";
});
</script>
</body>
</html>
