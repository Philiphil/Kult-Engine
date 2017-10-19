<?php

namespace kult_engine;

trait coreElement
{
	use debuggable;
    use settable{
    	settable::init as s_init;
    }

    public static function init($fnord=null){
    	static::s_init($fnord);
    	if(in_array(__NAMESPACE__."\hookable", class_uses(get_called_class()))) static::hook();
    }

}