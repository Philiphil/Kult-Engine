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

class vector implements \ArrayAccess, \Iterator, \Serializable, \JsonSerializable, \Countable
{
    private $_container = [];
    private $_properties = [];
    private $_position = 0;

    public function __construct(array $data = [])
    {
        $this->set($data);
    }

    public function set($array)
    {
        foreach ($array as $key => $value) {
            $this[$key] = $value;
        }
    }

    //ArrayAccess Implementation

    public function offsetExists($offset)
    {
        return isset($this->_container[$offset]);
    }

    public function offsetUnset($offset)
    {
        for ($i = $offset; $i < $this->count() - 1; $i++) {
            $this->_container[$i] = $this->_container[$i + 1];
        }
        unset($this->_container[$this->count() - 1]);
    }

    public function offsetGet($offset)
    {
        return isset($this->_container[$offset]) ? $this->_container[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_array($value)) {
            $value = new self($value);
        } //
        if ($offset === null) {
            $this->_container[] = $value;
        } else {
            $this->_container[$offset] = $value;
        }
    }

    //iterator impt
    public function rewind()
    {
        $this->_position = 0;
    }

    public function current()
    {
        return $this->_container[$this->_position];
    }

    public function key()
    {
        return $this->_position;
    }

    public function next()
    {
        $this->_position++;
    }

    public function valid()
    {
        return isset($this->_container[$this->_position]);
    }

    //Countable impt
    public function count()
    {
        return count($this->_container);
    }

    //JSON impt
    public function jsonSerialize()
    {
        return $this->_container;
    }

    public function __toJSON()
    {
        return json_encode($this->jsonSerialize());
    }

    //Other Array logics
    public function serialize()
    {
        return serialize($this->_container);
    }

    public function unserialize($data)
    {
        $this->_container = unserialize($data);
    }

    public function __toString()
    {
        return json_encode($this->_container);
    }

    public function &__toArray()
    {
        return $this->_container;
    }

    public function isEmpty()
    {
        return !$this->count() > 0;
    }

    public function __clone()
    {
        foreach ($this->_container as $key => $value) {
            if ($value instanceof self) {
                $this[$key] = clone $value;
            }
        }
    }

    //special properties
    public function __get($key)
    {
        if ($key === 'length') {
            return $this->count();
        }

        return $this->_properties[$key];
    }

    public function __set($key, $value)
    {
        $this->_properties[$key] = $value;
    }

    public function __isset($key)
    {
        return isset($this->_properties[$key]);
    }

    public function __unset($key)
    {
        unset($this->_properties[$key]);
    }

    //vector impt
    public function size()
    {
        return $this->count();
    }

    public function push_back($value)
    {
        $this[] = $value;
    }

    public function push_front($value)
    {
        if (is_array($value)) {
            $value = new self($value);
        } //
        $this->_container = array_merge([$value], $this->_container);
    }

    public function pop_back()
    {
        unset($this[$this->count() - 1]);
    }

    public function pop_front()
    {
        $this->offsetUnset(0);
    }

    public function swap($i, $j)
    {
        $fnord = $this[$i];
        $this[$i] = $this[$j];
        $this[$j] = $fnord;
    }

    public function clear()
    {
        $this->_container = [];
    }

    public function front()
    {
        return $this[0];
    }

    public function back()
    {
        return $this[$this->count() - 1];
    }
}
