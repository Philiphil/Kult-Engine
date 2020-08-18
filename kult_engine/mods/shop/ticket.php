<?php
include 'config.php';
page::standardpage_head('Ticket');
?>
<body>
<?php	page::standardpage_header(); ?>
		<section>
			<article>
<?php

    $trajet = unserialize(base64_decode($_POST['trajet']));

    $date = $_POST['date'];
    $dep = $_POST['dep'];
    $arrive = $_POST['arrive'];
    echo '<div id="ticketcontainer"><p>'.get_texte('trajetresume').'</p><br>';

    $tmp = $trajet->get_voyages();
    $bool1 = 0;
    $bool2 = 0;
    $compteur = 0;
    if ($trajet->get_quotidien()) {
        $trajet->set_quotidien(0);
        $fnord = substr($date, 0, -4);
        foreach ($tmp as $arret) {
            $foo = $arret->get_depart();
            $footu = $arret->get_arrive();
            $arret->set_depart($fnord.$foo);
            $arret->set_arrive($fnord.$footu);
        }
    }

    foreach ($tmp as $arret) {
        $ville = $arret->get_ville();
        if ($ville == $dep && !$bool1) {
            $bool1 = 1;
            echo '<p>'.$ville.' '.get_texte('à').' '.substr($arret->get_depart(), -4, 2).':'.substr($arret->get_depart(), -2).'</p>';
            echo '<div class="hidden arretclass" id="'.$compteur.'">';
            echo '<span class="ville">'.$ville.'</span>';
            echo '<span class="depart">'.$arret->get_depart().'</span>';
            echo '<span class="arrive">'.$arret->get_arrive().'</span>';
            echo '</div>';
            $compteur++;
        }
        if ($ville != $dep && $bool1 && !$bool2) {
            echo '<p>'.$ville.' '.get_texte('à').' '.substr($arret->get_arrive(), -4, 2).':'.substr($arret->get_arrive(), -2).'</p>';
            echo '<div class="hidden arretclass" id="'.$compteur.'">';
            echo '<span class="ville">'.$ville.'</span>';
            echo '<span class="depart">'.$arret->get_depart().'</span>';
            echo '<span class="arrive">'.$arret->get_arrive().'</span>';
            echo '</div>';
            $compteur++;
        }
        if ($ville == $arrive && !$bool2 && $bool1) {
            $bool2 = 1;
        }
    }

    echo '<br>'.get_texte('durée').' <span class="voyage_duree">'.$trajet->get_duree($dep, $arrive).'</span><br>'.
        get_texte('prix').' <span class="voyage_prix">'.$trajet->get_price($dep, $arrive).'</span>€<br>
		
		<br><br>
		<form action="https://www'.constant('sandbox').'.paypal.com/cgi-bin/webscr" method="post" id="form_paypal">
		<input type="hidden" value="'.$trajet->get_price($dep, $arrive).'" name="amount" />
		<input name="currency_code" type="hidden" value="EUR" />
		<input name="shipping" type="hidden" value="0.00" />
		<input name="tax" type="hidden" value="'.$trajet->get_price($dep, $arrive) * (constant('TVA')).'" />
		<input name="return" type="hidden" value="'.constant('url').'paiementValide.php" />
		<input name="cancel_return" type="hidden" value="'.constant('url').'voyage.php" />
		<input name="notify_url" type="hidden" value="'.constant('url').'validationpaiement.php" />
		<input name="cmd" type="hidden" value="_xclick" />
		<input name="business" type="hidden" value="'.constant('paypal').'" />
		<input name="item_name" type="hidden" value="Ticket" />
		<input name="no_note" type="hidden" value="1" />
		<input name="custom" type="hidden" value="ID_ACHETEUR" />
		<input name="lc" type="hidden" value="FR" />
		<input name="bn" type="hidden" value="PP-BuyNowBF" />
		<input tilte="'.get_texte('paypal_tilte').'" type="button" value="'.get_texte('btn_paypal').'" id="btn_paypal" type="image"/>
		</form>
		</div><div id="change"></div>';

?>
			</article>
		</section>
<?php page::standardpage_footer(); ?>
</body>