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

class Map extends vector
{
    private $_keys = [];

    public function offsetUnset($key)
    {
        if (is_int($key)) {
            for ($i = $key; $i < $this->count() - 1; $i++) {
                $this->_container[$this->_keys[$i]] = $this->_container[$this->_keys[$i + 1]];
                $this->_keys[$i] = $this->_keys[$i + 1];
            }
            unset($this->_container[$this->_keys[$this->count() - 1]]);
            unset($this->_keys[$this->_keys[$this->count() - 1]]);
        } else {
            unset($this->_container[$key]);
            unset($this->_keys[array_search($key, $this->_keys)]);
        }
    }

    public function offsetGet($key)
    {
        return $this->_container[$key] ?? null;
    }

    public function offsetSet($key, $value)
    {
        if (is_array($value)) {
            $value = new self($value);
        } //
        if ($key === null) {
            $this->_container[] = $value;
            $this->_keys[] = array_key_last($this->_container);
        } else {
            $this->_container[$key] = $value;
            if (!in_array($key, $this->_keys)) {
                $this->_keys[] = $key;
            }
        }
    }

    public function front()
    {
        return $this[$this->_keys[0]];
    }

    public function back()
    {
        return $this[$this->_keys[$this->count() - 1]];
    }

    public function pop_back(): void
    {
        $this->erase($this->_keys[$this->count() - 1]);
    }

    public function pop_front(): void
    {
        $this->erase($this->_keys[0]);
    }

    public function insert($key, $value): void
    {
        if (is_int($key)) {
            return parent::insert($key, $value);
        }
        if (is_array($value)) {
            $value = new self($value);
        } //
        $this[$key] = $value;
    }
}
