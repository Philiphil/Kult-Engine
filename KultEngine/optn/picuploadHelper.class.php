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
 * @copyright Copyright (c) 2016-2020, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace KultEngine;

class picuploadHelper extends uploadHelper
{
    public $_max_width = 0;
    public $_max_height = 0;

    public function __construct($fnord)
    {
        parent::__construct($fnord);
        $this->_authorize_extentions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];
    }

    public function run()
    {
        if (!parent::verif()) {
            return false;
        }
        if ($this->verif()) {
            $this->_extention = $this->_convertion ? $this->_convertion : $this->_extention;

            return move_uploaded_file($this->_file['tmp_name'], $this->_dest_folder.$this->_name.'.'.$this->_extention);
        }

        return $this->adjust();
    }

    public function verif()
    {
        $size = getimagesize($this->_file['tmp_name']);
        $w = $size[0];
        $h = $size[1];
        if ($w > $this->_max_width || $h > $this->_max_height) {
            return 0;
        }

        return 1;
    }

    public function adjust()
    {
        $size = getimagesize($this->_file['tmp_name']);
        $w = $size[0];
        $h = $size[1];
        $d_w = $w - $this->_max_width;
        $d_h = $h - $this->_max_height;
        if ($d_h > $d_w) {
            $foo = $h - $this->_max_height;
            $fnord = $foo / $h;
            $n_w = $w - ($w * $fnord);
            $n_h = $this->_max_height;
        } else {
            $foo = $w - $this->_max_width;
            $fnord = $foo / $w;
            $n_h = $h - ($h * $fnord);
            $n_w = $this->_max_width;
        }
        $bfr = '';
        switch ($this->_extention) {
                case 'jpg':
                case 'jpeg':
                    $bfr = imagecreatefromjpeg($this->_file['tmp_name']);
                    break;
                case 'png':
                    $bfr = imagecreatefrompng($this->_file['tmp_name']);
                    break;
                case 'gif':
                    $bfr = imagecreatefromgif($this->_file['tmp_name']);
                    break;
                case 'bmp':
                    $bfr = imagecreatefrombmp($this->_file['tmp_name']);
                    break;
            }
        $this->_extention = $this->_convertion ? $this->_convertion : $this->_extention;
        $this->_fullpath = $this->_dest_folder.$this->_name.'.'.$this->_extention;
        $ptr = imagecreatetruecolor($n_w, $n_h);
        imagecopyresampled($ptr, $bfr, 0, 0, 0, 0, $n_w, $n_h, $w, $h);
        switch ($this->_extention) {
                case 'jpg':
                case 'jpeg':
                    $bfr = imagejpeg($ptr, $this->_fullpath);
                    break;
                case 'png':
                    $bfr = imagepng($ptr, $this->_fullpath);
                    break;
                case 'gif':
                    $bfr = imagegif($ptr, $this->_fullpath);
                    break;
                case 'bmp':
                    $bfr = imagebmp($ptr, $this->_fullpath);
                    break;
            }

        return 1;
    }
}
