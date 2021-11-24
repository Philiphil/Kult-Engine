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
 * @copyright Copyright (c) 2016-2021, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace KultEngine;

abstract class shopText
{
    public static function text()
    {
        $array['fr']['create_shop_tilte'] = 'Creation de boutique';
        $array['en']['create_shop_tilte'] = 'Create shop';

        $array['fr']['create_shop_1'] = 'Vous devez creer un compte administrateur';
        $array['en']['create_shop_1'] = 'You must create an administrator account';

        $array['fr']['mail'] = 'email';
        $array['en']['mail'] = 'email';

        $array['fr']['supprimer'] = 'supprimer';
        $array['en']['supprimer'] = 'delete';

        $array['fr']['ok'] = 'ok';
        $array['en']['ok'] = 'ok';

        $array['fr']['no_same_pwd'] = 'Mot de passe non identique';
        $array['en']['no_same_pwd'] = 'non matching password';

        $array['fr']['wrong_mail'] = 'Adresse mail invalide';
        $array['en']['wrong_mail'] = 'invalid mail address';

        $array['fr']['wrong_pass'] = 'Combinaison mot de passe/adresse mail incorrecte !';
        $array['en']['wrong_pass'] = 'wrong pass/user combinaison';

        $array['fr']['submit'] = 'Envoyer';
        $array['en']['submit'] = 'Submit';

        $array['fr']['login'] = 'Se connecter';
        $array['en']['login'] = 'Log in';

        $array['fr']['signin'] = 'Inscription';
        $array['en']['signin'] = 'Sign in';

        $array['fr']['infoliv'] = 'Informations de livraison';
        $array['en']['infoliv'] = 'Delivery Infos';

        $array['fr']['password'] = 'mot de passe';
        $array['en']['password'] = 'password';

        $array['fr']['admin_page'] = 'Page Admin';
        $array['en']['admin_page'] = 'Admin Page';

        $array['fr']['productname'] = 'nom du produit';
        $array['en']['productname'] = 'product name';

        $array['fr']['price'] = 'prix';
        $array['en']['price'] = 'price';

        $array['fr']['buy'] = 'Acheter';
        $array['en']['buy'] = 'Buy';

        $array['fr']['seeproducts'] = 'voir produit';
        $array['en']['seeproducts'] = 'see product';

        $array['fr']['seemagasin'] = 'voir magasin';
        $array['en']['seemagasin'] = 'see shop';

        $array['fr']['tags'] = 'tags';
        $array['en']['tags'] = 'tags';

        $array['fr']['reduction'] = 'reduction';
        $array['en']['reduction'] = 'reduction';

        $array['fr']['nppic'] = 'photos du produits';
        $array['en']['nppic'] = "item's pictures";

        $array['fr']['magasinname'] = 'Nom de la boutique';
        $array['en']['magasinname'] = "Shop's name";

        $array['fr']['country'] = 'Pays';
        $array['en']['country'] = 'Country';

        $array['fr']['street'] = 'Rue';
        $array['en']['street'] = 'Street';

        $array['fr']['city'] = 'Ville';
        $array['en']['city'] = 'City';

        $array['fr']['nom'] = 'Nom';
        $array['en']['nom'] = 'Nom';

        $array['fr']['prenom'] = 'Prenom';
        $array['en']['prenom'] = 'Prenom';

        $array['fr']['ban'] = 'bannir';
        $array['en']['ban'] = 'ban';

        $array['fr']['unban'] = 'débannir';
        $array['en']['unban'] = 'unban';

        $array['fr']['giveadmin'] = 'donner droit admin';
        $array['en']['giveadmin'] = 'give admin rights';

        $array['fr']['takeadmin'] = 'reprendre droit admin';
        $array['en']['takeadmin'] = 'take admin rights';

        $array['fr']['seeuser'] = 'voir utilisateurs';
        $array['en']['seeuser'] = 'see users';

        $array['fr']['inventaire'] = 'Inventaire';
        $array['en']['inventaire'] = 'Inventory';

        $array['fr']['qtt'] = 'Quantité';
        $array['en']['qtt'] = 'Quantity';

        $array['fr']['total'] = 'Total';
        $array['en']['total'] = 'Total';

        $array['fr']['addproduct'] = 'Ajout de produit';
        $array['en']['addproduct'] = 'Add product';

        $array['fr']['addmagasin'] = 'Ajout de magasin';
        $array['en']['addmagasin'] = 'Add shop';

        $array['fr']['seecomande'] = 'voir commande';
        $array['en']['seecomande'] = 'see order';

        $array['fr']['commande'] = 'Bon de commande pour ';
        $array['en']['commande'] = 'Order for ';

        $array['fr']['subcommande'] = 'valider commande';
        $array['en']['subcommande'] = 'validate order';

        return $array;
    }

    public static function writeTotemplate($template, $option = [])
    {
        $template = preg_replace_callback("/\.*kt:!(.*):!/", function ($match) {
            return self::get_text($match[1]) === null ? $match[1] : self::get_text($match[1]);
        }, $template);
        $template = preg_replace_callback("/\.*kc:!(.*):!/", function ($match) {
            return constant($match[1]) === null ? $match[1] : constant($match[1]);
        }, $template);
        $template = preg_replace_callback("/\.*ko:!(.*):!/", function ($match) use ($option) {
            return !isset($option[$match[1]]) ? $match[1] : $option[$match[1]];
        }, $template);
        $template = preg_replace_callback("/\.*kod:!(.*):!/", function ($match) use ($option) {
            return !isset($option[$match[1]]) ? '' : $option[$match[1]];
        }, $template);
        buffer::delete();
        buffer::store();
        include constant('imptpath').'javascript.php';
        $js = buffer::get();
        buffer::store();
        $template = str_replace('</body>', $js.'</body>', $template);

        return $template;
    }

    public static function get_text($text)
    {
        $a = self::text();

        return $a[text::getLang()][$text];
    }
}
