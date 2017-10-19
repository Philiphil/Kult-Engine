<?php
	require_once('config.php');
	page::import_require();
	require constant('PHPMAILER');


	$req = 'cmd=_notify-validate';
	foreach ($_POST as $key => $value) {
		$value = urlencode(stripslashes($value));
		$req .= "&$key=$value";
	}
$header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
$header .= "Host: www".constant('sandbox').".paypal.com:443\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$header .= "Connection: close\r\n";
$fp = fsockopen ('ssl://www'.constant('sandbox').'.paypal.com', 443, $errno, $errstr, 30);
    $item_name = $_POST['item_name'];
    $item_number = $_POST['item_number'];
    $payment_status = $_POST['payment_status'];
    $payment_amount = $_POST['mc_gross'];
    $payment_currency = $_POST['mc_currency'];
    $txn_id = $_POST['txn_id'];
    $receiver_email = $_POST['receiver_email'];
    $payer_email = $_POST['payer_email'];
    $id_user = $_POST['custom'];          
      if (!$fp) {
        } else {
            fputs ($fp, $header . $req);
            while (!feof($fp)) {

                $res = fgets ($fp, 1024);
                $bool=1;
                if (!connector::ticket_get_valide($id_user) && (strcmp ($res, "VERIFIED") == 1)) {
                    connector::ticket_validation($id_user);



    $trajet = connector::ticket_get_trajet($id_user);
    $tmp=$trajet->get_voyages();
    $dep=$tmp[0]->get_ville();
    $arrive=$tmp[count($tmp)-1]->get_ville();

$pdf = new FPDF();
$pdf->AddPage();
define('EURO', chr(128));
$titre = get_texte('railcommander');
$pdf->SetFont('Arial','B',16);
$w = $pdf->GetStringWidth($titre)+6;
$pdf->SetX((210-$w)/2);
$pdf->SetLineWidth(1);
$pdf->Cell($w,9,$titre,1,1,'C');
$pdf->Ln(10);
$paymenttax=$payment_amount-$trajet->get_price($dep,$arrive);
$pdf->SetFont('Arial', 'i',12);

$pdf->Cell(0,5, utf8_decode(substr($tmp[0]->get_depart(),0, -5)),0,1);
    foreach($tmp as $arret)
        {
            $pdf->Cell(0,5, utf8_decode($arret->get_ville().' '.get_texte("à").' '.substr($arret->get_depart(), -4,2).':'.substr($arret->get_depart(), -2)),0,1);
        }   
        $pdf->Ln(10);
        $pdf->SetFont('Arial','',14);
        $w = $pdf->GetStringWidth($titre)+6;
        $pdf->Cell(190, 6,utf8_decode(get_texte("durée").' '.$trajet->get_duree($dep,$arrive)),0,1,'C');
        $pdf->Cell(190 ,6,utf8_decode(get_texte("prixht").' '.$trajet->get_price($dep,$arrive)).constant('EURO'),0,1,'C');
        $pdf->Cell(190 ,6,utf8_decode(get_texte("taxe").' '.$paymenttax).constant('EURO'),0,1,'C');
        $pdf->Cell(190 ,6,utf8_decode(get_texte("prixttc").' '.$payment_amount).constant('EURO'),0,1,'C');
        $pdf->SetFont('Arial','B',16);
        $pdf->Ln(5);
        $pdf->Cell(185 ,10,utf8_decode($id_user),0,1,'C');
        $pdf->SetFont('Arial','i',11);
          $pdf->Ln(10);
        $pdf->MultiCell(190 ,5,utf8_decode(get_texte("pdf_mention")),0,1);
        
                            $mail = new PHPMailer;
                            $mail->isSMTP();
                            $mail->Host = constant('smtp');  // Specify main and backup SMTP servers
                            $mail->Port = 587;     
                            $mail->SMTPSecure = 'tls'; 
                            $mail->setFrom('bot@'.constant('domaine'),'Mailer');
                            $mail->addAddress($payer_email);
                            $mail->isHTML(true);                                  // Set email format to HTML
                            $mail->Subject = get_texte('email_sujet');
                            $mail->Body    = get_texte('email_body');
                            $mail->AltBody = get_texte('email_body');

                                    $doc = $pdf->Output('S');
        $mail->AddStringAttachment($doc, 'ticket.pdf', 'base64', 'application/pdf');
        
                            $mail->send();














                  }
            }
            fclose ($fp);
        }