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

namespace kult_engine;

abstract class shop
{
    use CoreElementTrait;

    public static function get_current_shop()
    {
        $d = new daoGenerator(new magasin());

        return $d->select($_SESSION['cart_location']);
    }

    public static function reset_shop()
    {
        $d = new daoGenerator(new shopClient());
        $d->delete_table();
        $d(new magasin());
        $d->delete_table();
        $d(new product());
        $d->delete_table();
        $d(new commande());
        $d->delete_table();
        self::create_shop();
    }

    public static function create_shop()
    {
        $header = shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'admin_header.php'));

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $d = new daoGenerator(new shopClient());
            $d->create_table();

            $d(new magasin());
            $d->create_table();

            $d(new product());
            $d->create_table();

            $d(new commande());
            $d->create_table();
            echo shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'create_shop.php'), ['admin'=>$header]);
        } else {
            $c = new shopClient();
            $d = new daoGenerator(new shopClient());
            $c->_mail = $_POST['mail'];
            $c->_password = $_POST['password'];
            $c->_admin = 1;
            $d->set($c);
            echo shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'create_shop.php'), ['admin'=>$header]);
        }
    }

    public static function admin()
    {
        $header = shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'admin_header.php'));
        $commandes = new daoGenerator(new commande());
        $c = $commandes->select_all(0, '_statut');
        $o = [];
        $o['admin'] = $header;
        if (count($c) > 0) {
            $o['commandes'] = count($c);
        }

        echo shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'admin.php'), $o);
        router::set_route('/add_product/', function () use ($header) {
            buffer::delete();
            echo shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'product_admin_new.php'), ['admin'=>$header]);
        });

        router::set_route('/see_user/', function () use ($header) {
            buffer::delete();
            $d = new daoGenerator(new shopClient());
            $us = $d->get_all();
            $html = '';
            foreach ($us as $u) {
                $b = $u->_banned ? shopText::get_text('unban') : shopText::get_text('ban');
                $a = $u->_admin ? shopText::get_text('takeadmin') : shopText::get_text('giveadmin');
                $html .= '<p>User : '.$u->_mail." <a href='".constant('url').'/adminuser/'.$u->_id."' >".$a."</a> <a href='".constant('url').'/banuser/'.$u->_id."' >".$b.'</a></p>';
            }
            echo shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'empty_admin.php'), ['this'=>$html, 'admin' => $header]);
        });

        router::set_route('/banuser/k:!1', function ($a) use ($header) {
            buffer::delete();
            $d = new daoGenerator(new shopClient());
            $u = $d->select($a, '_id');
            $u->_banned = !$u->_banned;
            $d->set($u);
            $us = $d->get_all();
            $html = '';
            foreach ($us as $u) {
                $b = $u->_banned ? shopText::get_text('unban') : shopText::get_text('ban');
                $a = $u->_admin ? shopText::get_text('takeadmin') : shopText::get_text('giveadmin');
                $html .= '<p>User : '.$u->_mail." <a href='".constant('url').'/adminuser/'.$u->_id."' >".$a."</a> <a href='".constant('url').'/banuser/'.$u->_id."' >".$b.'</a></p>';
            }
            echo shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'empty_admin.php'), ['this'=>$html, 'admin' => $header]);
        });

        router::set_route('/adminuser/k:!1', function ($a) use ($header) {
            buffer::delete();
            $d = new daoGenerator(new shopClient());
            $u = $d->select($a, '_id');
            $u->_admin = !$u->_admin;
            $d->set($u);
            $us = $d->get_all();
            $html = '';
            foreach ($us as $u) {
                $b = $u->_banned ? shopText::get_text('unban') : shopText::get_text('ban');
                $a = $u->_admin ? shopText::get_text('takeadmin') : shopText::get_text('giveadmin');
                $html .= '<p>User : '.$u->_mail." <a href='".constant('url').'/adminuser/'.$u->_id."' >".$a."</a> <a href='".constant('url').'/banuser/'.$u->_id."' >".$b.'</a></p>';
            }
            echo shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'empty_admin.php'), ['this'=>$html, 'admin' => $header]);
        });

        router::set_route('/add_magasin/', function () use ($header) {
            buffer::delete();
            echo shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'magasin_admin_new.php'), ['admin'=>$header]);
        });

        router::set_route('/see_magasin/', function () use ($header) {
            buffer::delete();
            $d = new daoGenerator(new magasin());
            $v = $d->get_all();
            $html = '';
            foreach ($v as $key) {
                $html .= '<p>'.$key->_nom[text::getLang()]." <a href='".constant('url').'/inventory/'.$key->_id."'>".shopText::get_text('inventaire')."</a> <a href='".constant('url').'/del_magasin/'.$key->_id."del_magasin'>".shopText::get_text('supprimer').'</a></p>';
            }
            echo shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'empty_admin.php'), ['this'=>$html, 'admin' => $header]);
        });

        router::set_route('/del_magasin/k:!1', function ($a) use ($header) {
            buffer::delete();
            $d = new daoGenerator(new magasin());
            $v = $d->select($a, '_id');
            $d->delete($v);
            $html = '';
            $v = $d->get_all();
            foreach ($v as $key) {
                $html .= '<p>'.$key->_nom[text::getLang()]." <a href='".constant('url').'/inventory/'.$key->_id."'>".shopText::get_text('inventaire')."</a> <a href='".constant('url').'/del_magasin/'.$key->_id."del_magasin'>".shopText::get_text('supprimer').'</a></p>';
            }
            echo shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'empty_admin.php'), ['this'=>$html, 'admin' => $header]);
        });

        router::set_route('/inventory/k:!1', function ($a) use ($header) {
            buffer::delete();

            $d = new daoGenerator(new magasin());
            $m = $d->select($a, '_id');
            $d(new product());
            $ps = $d->get_all();
            $html = '';
            $html .= '<form method="POST" id="form" class="k_input" style="margin-right:auto;margin-left:auto;" enctype="multipart/form-data"><input type="hidden" name="fonc" value="edit_inventory">';
            $html .= '	<input type="hidden" name="shop" value="'.$a.'">';
            foreach ($ps as $p) {
                $q = isset($m->_inventory[$p->_id]) ? $m->_inventory[$p->_id] : '';
                $html .= '<p>'.$p->_name[text::getLang()]." : <input type='text' name='p_".$p->_id."' value='".$q."' class='k_input' />";
            }
            $html .= "<br><input type='submit' value='ok' class='k_input' ></form>";
            echo shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'empty_admin.php'), ['this'=>$html, 'admin' => $header]);
        });

        router::set_route('/see_products/', function () use ($header) {
            buffer::delete();
            $d = new daoGenerator(new product());
            $v = $d->get_all();
            $html = '';
            foreach ($v as $key) {
                $html .= '<p>'.$key->_name[text::getLang()]."<a href='".constant('url').'/del_product/'.$key->_id."'>".shopText::get_text('supprimer').'</a></p>';
            }
            echo shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'empty_admin.php'), ['this'=>$html, 'admin' => $header]);
        });

        router::set_route('/del_product/k:!1', function ($a) use ($header) {
            buffer::delete();
            $d = new daoGenerator(new product());
            $v = $d->select($a, '_id');
            $d->delete($v);
            $html = '';
            foreach ($v->_pic as $key) {
                unlink(constant('viewpath').'content'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$key);
            }

            $v = $d->get_all();
            foreach ($v as $key) {
                $html .= '<p>'.$key->_name[text::getLang()]." <a href='".constant('url').'/del_product/'.$key->_id."'>".shopText::get_text('supprimer').'</a></p>';
            }
            echo shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'empty_admin.php'), ['this'=>$html, 'admin' => $header]);
        });

        router::set_route('/see_commande/', function () use ($header) {
            buffer::delete();
            $d = new daoGenerator(new commande());
            $us = $d->get_all();
            $html = '';
            foreach ($us as $u) {
                $ls = "<div class='k_block'>";
                $ls .= "<div class='k_block'>";
                $d = array_count_values($u->_products);
                $done = [];
                $y = 0;
                foreach ($u->_products as $key) {
                    if (in_array($key, $done)) {
                        continue;
                    }
                    $vk = json_decode($key, true);
                    $ls .= '<span>'.$vk['_name'][text::getLang()].'&nbsp;&nbsp;&nbsp;&nbsp;';
                    $ls .= $vk['_price'].currency_html.'&nbsp;&nbsp;&nbsp;&nbsp;';
                    $ls .= shopText::get_text('qtt').' : '.$d[$key].'&nbsp;&nbsp;&nbsp;&nbsp;';
                    $ls .= shopText::get_text('total').' : '.($y += ($d[$key] * ($vk['_price'] - $vk['_reduction'] * $vk['_price'] / 100))).currency_html.'</span>';
                    $done[] .= $key;
                }
                $ls .= '<p>'.shopText::get_text('total').' : '.$y.currency_html.'</p>';
                $ls .= '</div>';
                $cl = json_decode($u->_user, true);
                $ls .= '<p>'.$cl['_nom'].' '.$cl['_prenom'];
                $ls .= '<br>('.$cl['_mail'];
                $ls .= ')<br>'.$cl['_rue'];
                $ls .= '<br>'.$cl['_ville'];
                $ls .= '<br>'.$cl['_pays'].'</p>';
                if ($u->_statut == 0) {
                    $ls .= "<a href='".constant('url')."/set_commande/$u->_id/1'>".shopText::get_text('subcommande').'</a>';
                }
                if ($u->_statut == 1) {
                    $s = new secureSerial();
                    $e = $s->serialize($u);
                    $e = base64_encode($e);
                    $ls .= "<a href='/bon.php?order=$e'>".shopText::get_text('seecomande').'</a>';
                }
                $ls .= '</div>';
                $html .= $ls;
            }
            echo shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'empty_admin.php'), ['this'=>$html, 'admin' => $header]);
        });

        router::set_route('/set_commande/k:!1/k:!2', function ($a, $b) use ($header) {
            buffer::delete();

            $d = new daoGenerator(new commande());
            $c = $d->select($a);
            $c->_statut = 1;
            $d->set($c);
            $html = shopText::get_text('ok');
            echo shopText::writeTotemplate(file_get_contents(constant('shoptemplate').'empty_admin.php'), ['this'=>$html, 'admin' => $header]);
        });

        if (isset($_POST['fonc'])) {
            switch ($_POST['fonc']) {
                case 'edit_inventory':
                     $d = new daoGenerator(new product());
                     $ps = $d->get_all();
                     $er = new daoGenerator(new magasin());
                    $m = $er->select($_POST['shop'], '_id');
                     foreach ($ps as $p) {
                         $m->_inventory[$p->_id] = intval($_POST['p_'.$p->_id]);
                     }
                     $er->set($m);
                break;
                case 'new_product':
                    $d = new daoGenerator(new product());
                    $p = new product();
                    foreach (config::$server_lang as $key) {
                        $p->_name[$key] = $_POST['name_'.$key];
                        $p->_description[$key] = $_POST['description_'.$key];
                    }
                    $p->_price = $_POST['price'];
                    $p->_tags = $_POST['tags'];
                    $p->_reduction = $_POST['reduction'];
                    $files = normalize_files($_FILES);
                    $i = 0;
                    foreach ($files as $key) {
                        $v = new picuploadHelper($key);
                        $v->_max_width = 500;
                        $v->_max_height = 500;
                        $v->_name = $p->_name[config::$default_lang].'_'.$i;
                        $v->_dest_folder = constant('viewpath').'content'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR;
                        $v->run();
                        $i++;
                        $p->_pic[] = $v->_name.'.'.$v->_extention;
                    }
                    $d->set($p);
                break;
                case 'new_magasin':
                    $d = new daoGenerator(new magasin());
                    $m = new magasin();
                    foreach (config::$server_lang as $key) {
                        $m->_nom[$key] = $_POST['name_'.$key];
                    }
                    $m->_pays = $_POST['pays'];
                    $m->_rue = $_POST['rue'];
                    $m->_ville = $_POST['ville'];
                    $d->set($m);
                break;
            }
        }
    }

    /**/
}
