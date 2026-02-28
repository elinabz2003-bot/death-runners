<?php
/**
 * Conditions d'Utilisation - Death Runners
 *
 * En accédant à Death Runners, vous acceptez les conditions d'utilisation décrites ci-dessous.
 */


if (!isset($racine_path)) {
    $racine_path = './';
}

ob_start();
?>

<section class="terms-section" style="padding: 40px 0; background: #121212;">
    <div class="terms-container" style="max-width: 800px; margin: 0 auto; background: #1a1a1a; border: 2px solid #00ffcc; border-radius: 8px; padding: 30px;">
        <h1 class="terms-title" style="text-align: center; font-size: 2.8em; color: #00ffcc; margin-bottom: 20px;">Conditions d'Utilisation</h1>
        
        <h2 class="terms-heading" style="color: #00ffcc; border-bottom: 1px solid #00ffcc; padding-bottom: 8px;">1. Bienvenue, Runner !</h2>
        <p class="terms-text" style="font-size: 1.1em; line-height: 1.6; margin: 20px 0;">
            Préparez-vous à plonger dans l'arène de <strong>Death Runners</strong> ! En accédant à ce site, vous acceptez de respecter les règles de la course. Si vous n'êtes pas prêt à relever le défi, restez à l'écart.
        </p>
        
        <h2 class="terms-heading" style="color: #00ffcc; border-bottom: 1px solid #00ffcc; padding-bottom: 8px;">2. Accès et Engagement</h2>
        <ul class="terms-list" style="font-size: 1.1em; line-height: 1.6; margin: 20px 0;">
            <li><strong>Accès gratuit :</strong> Death Runners est accessible gratuitement. Nous nous réservons le droit de suspendre l'accès en cas de comportement inapproprié.</li>
            <li><strong>Votre engagement :</strong> Vous devez fournir des informations exactes lors de l'inscription et garder vos identifiants confidentiels. Toute tentative de triche vous bannira.</li>
        </ul>
        
        <h2 class="terms-heading" style="color: #00ffcc; border-bottom: 1px solid #00ffcc; padding-bottom: 8px;">3. Propriété et Code</h2>
        <ul class="terms-list" style="font-size: 1.1em; line-height: 1.6; margin: 20px 0;">
            <li><strong>Contenu exclusif :</strong> Tout le contenu du site, y compris le code source, les images et les vidéos, est la propriété exclusive de Death Runners et de ses partenaires.</li>
            <li><strong>Utilisation limitée :</strong> Vous pouvez consulter et imprimer des pages pour un usage personnel, mais toute reproduction commerciale est interdite.</li>
        </ul>
        
        <h2 class="terms-heading" style="color: #00ffcc; border-bottom: 1px solid #00ffcc; padding-bottom: 8px;">4. Règles de la Course</h2>
        <p class="terms-text" style="font-size: 1.1em; line-height: 1.6; margin: 20px 0;">
            Jouez de manière fair-play : ne trichez pas, n'exploitez pas les failles du système et ne perturbez pas le fonctionnement du site. Le non-respect des règles entraînera des sanctions sévères.
        </p>
        
        <h2 class="terms-heading" style="color: #00ffcc; border-bottom: 1px solid #00ffcc; padding-bottom: 8px;">5. Confidentialité et Sécurité</h2>
        <ul class="terms-list" style="font-size: 1.1em; line-height: 1.6; margin: 20px 0;">
            <li><strong>Politique de Confidentialité :</strong> Vos données personnelles sont traitées selon notre politique stricte de confidentialité.</li>
            <li><strong>Sécurité :</strong> Nous utilisons des mesures de sécurité avancées pour protéger vos informations, bien qu'une sécurité absolue ne soit pas garantie.</li>
        </ul>
        
        <h2 class="terms-heading" style="color: #00ffcc; border-bottom: 1px solid #00ffcc; padding-bottom: 8px;">6. Mises à Jour</h2>
        <p class="terms-text" style="font-size: 1.1em; line-height: 1.6; margin: 20px 0;">
            Death Runners se réserve le droit de modifier ces conditions à tout moment. Les modifications seront effectives dès leur publication sur le site. Consultez régulièrement cette page pour rester informé.
        </p>
        
        <h2 class="terms-heading" style="color: #00ffcc; border-bottom: 1px solid #00ffcc; padding-bottom: 8px;">7. Limitation de Responsabilité</h2>
        <p class="terms-text" style="font-size: 1.1em; line-height: 1.6; margin: 20px 0;">
            Le site est fourni "tel quel", sans garantie d'absence d'erreurs. Death Runners ne pourra être tenu responsable des interruptions ou pertes de données.
        </p>
        
        <h2 class="terms-heading" style="color: #00ffcc; border-bottom: 1px solid #00ffcc; padding-bottom: 8px;">8. Liens Externes</h2>
        <p class="terms-text" style="font-size: 1.1em; line-height: 1.6; margin: 20px 0;">
            Le site peut inclure des liens vers des sites tiers. Nous ne sommes pas responsables de leur contenu ni de leurs politiques.
        </p>
        
        <h2 class="terms-heading" style="color: #00ffcc; border-bottom: 1px solid #00ffcc; padding-bottom: 8px;">9. Droit Applicable</h2>
        <p class="terms-text" style="font-size: 1.1em; line-height: 1.6; margin: 20px 0;">
            Ces conditions sont régies par le droit français. En cas de litige, seuls les tribunaux français seront compétents.
        </p>
        
        <h2 class="terms-heading" style="color: #00ffcc; border-bottom: 1px solid #00ffcc; padding-bottom: 8px;">10. Contact</h2>
        <p class="terms-text" style="font-size: 1.1em; line-height: 1.6; margin: 20px 0;">
            Pour toute question concernant ces conditions, contactez-nous :
        </p>
        <p class="terms-contact" style="font-size: 1.1em; line-height: 1.6;">
            <strong>Email :</strong> Jonathan.Weninger@hotmail.fr<br>
            <strong>Adresse :</strong> Avignon, France.
        </p>
    </div>
</section>

<?php
$main = ob_get_clean();
$titre = "Conditions d'Utilisation - Death Runners";


include($racine_path . 'templates/front/header.php');
include($racine_path . 'templates/front/main.php');
include($racine_path . 'templates/front/footer.php');
?>
