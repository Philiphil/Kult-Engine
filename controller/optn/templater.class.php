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


class templater
{
    public $_templates = [];

    public function load($template)
    {
    	if(isset($this->_templates[$template])) return $this->_templates[$template];
        $impt = scandir(constant('tpltpath'));
        for ($i=0; $i < count($impt) ; $i++) {

        	if ( $impt[$i] === $template || substr(strstr($impt[$i], DIRECTORY_SEPARATOR, false),1) === $template  ) {
                $this->_templates[$template] = $this->write_to_template(file_get_contents(constant('tpltpath').$impt[$i]), [], 1);
                return $this->_templates[$template];
            }else if( is_dir(constant("tpltpath").$impt[$i]) &&
            ( $impt[$i] != "." && $impt[$i] != "..")){
                $tmp = scandir(constant('tpltpath').$impt[$i]);
                $bfr=[];
                foreach ($tmp as $key) {
                    if($key == "." ||$key == "..")continue;
                    $impt[count($impt)] = $impt[$i].DIRECTORY_SEPARATOR.$key;
                }
            }
        }
        return;
    }

    public function write_to_template($template, $option = [],$load=false)
    {
    	if(!$load)$template = $this->load($template);
    	if(!$template)return $template;
    	if($load){
	        $template = preg_replace_callback("/\.*kt:!(.[^:!]*):!/", function ($match) {
	            return text::get_text($match[1]) === null ? $match[1] : text::get_text($match[1]);
	        }, $template);
	        $template = preg_replace_callback("/\.*kc:!(.[^:!]*):!/", function ($match) {
	            return constant($match[1]) === null ? $match[1] : constant($match[1]);
	        }, $template);
	    }else{
	        $template = preg_replace_callback("/\.*ko:!(.[^:!]*):!/", function ($match) use ($option) {
	            return !isset($option[$match[1]]) ? $match[1] : $option[$match[1]];
	        }, $template);
	        $template = preg_replace_callback("/\.*kod:!(.[^:!]*):!/", function ($match) use ($option) {
	            return !isset($option[$match[1]]) ? '' : $option[$match[1]];
	        }, $template);
        /*$template = preg_replace_callback("/\.*ktp:!(.[^:!]*):!/", function ($match) {
            if ($this->_templates === null) {
                $this->load();
            }
            return isset($this->_templates[$match[1]]) ? $this->_templates[$match[1]] : $match[1];
        }, $template);*/
    	}
        return $template;
    }
}

