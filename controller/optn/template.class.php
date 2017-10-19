<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 *
 * Copyright (c) 2016-2017
 *
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
 * @copyright Copyright (c) 2016-2017, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace kult_engine;

class template
{
    public $_templates = null;

    public function load()
    {
        $impt = scandir(constant('tpltpath'));
        foreach ($impt as $key) {
            if (contains('.load.', $key)) {
                $this->_templates[strstr($key, '.load.', true)] = $this->writeTotemplate(file_get_contents(constant('tpltpath').$key));
            }
        }
    }

    public function writeTotemplate($template, $option = [])
    {
        $template = preg_replace_callback("/\.*kt:!(.*):!/", function ($match) {
            return text::get_text($match[1]) === null ? $match[1] : text::get_text($match[1]);
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
        $template = preg_replace_callback("/\.*ktp:!(.*):!/", function ($match) {
            if ($this->_templates === null) {
                $this->load();
            }

            return isset($this->_templates[$match[1]]) ? $this->_templates[$match[1]] : $match[1];
        }, $template);

        return $template;
    }
}
