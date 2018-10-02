<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package Kult Engine
 * @author Théo Sorriaux (philiphil)
 * @copyright Copyright (c) 2016-2018, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

include 'config.php';
    page::header();
        $trajet = connector::ticket_get_trajet($_POST['custom']);
    $tmp = $trajet->get_voyages();
    $dep = $tmp[0]->get_ville();
    $arrive = $tmp[count($tmp) - 1]->get_ville();

$pdf = new FPDF();
$pdf->AddPage();
define('EURO', chr(128));
$titre = get_texte('railcommander');
$pdf->SetFont('Arial', 'B', 16);
$w = $pdf->GetStringWidth($titre) + 6;
$pdf->SetX((210 - $w) / 2);
$pdf->SetLineWidth(1);
$pdf->Cell($w, 9, $titre, 1, 1, 'C');
$pdf->Ln(10);

$paymenttax = $_POST['mc_gross'] - $trajet->get_price($dep, $arrive);
$pdf->SetFont('Arial', 'i', 12);

$pdf->Cell(0, 5, utf8_decode(substr($tmp[0]->get_depart(), 0, -5)), 0, 1);
    foreach ($tmp as $arret) {
        $pdf->Cell(0, 5, utf8_decode($arret->get_ville().' '.get_texte('à').' '.substr($arret->get_depart(), -4, 2).':'.substr($arret->get_depart(), -2)), 0, 1);
    }
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 14);
        $w = $pdf->GetStringWidth($titre) + 6;
        $pdf->Cell(190, 6, utf8_decode(get_texte('durée').' '.$trajet->get_duree($dep, $arrive)), 0, 1, 'C');
        $pdf->Cell(190, 6, utf8_decode(get_texte('prixht').' '.$trajet->get_price($dep, $arrive)).constant('EURO'), 0, 1, 'C');
        $pdf->Cell(190, 6, utf8_decode(get_texte('taxe').' '.$paymenttax).constant('EURO'), 0, 1, 'C');
        $pdf->Cell(190, 6, utf8_decode(get_texte('prixttc').' '.$_POST['mc_gross']).constant('EURO'), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Ln(5);
        $pdf->Cell(185, 10, utf8_decode($id_user), 0, 1, 'C');
        $pdf->SetFont('Arial', 'i', 11);
          $pdf->Ln(10);
        $pdf->MultiCell(190, 5, utf8_decode(get_texte('pdf_mention')), 0, 1);
 $pdf->Output();
