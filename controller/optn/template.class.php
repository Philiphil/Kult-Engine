<?php
namespace kult_engine;

class template{
	public $_templates=null;

	public function load()
	{
        $impt = scandir(constant("tpltpath"));
        foreach ($impt as $key) {
            if(contains(".load.", $key)){
            	$this->_templates[strstr($key, ".load.", true)] = $this->writeTotemplate(file_get_contents(constant("tpltpath").$key));
        }
	}

	public function writeTotemplate($template,$option=[])
	{
		$template= preg_replace_callback("/\.*kt:!(.*):!/",function($match){return text::get_text($match[1]) === null ? $match[1] : text::get_text($match[1]);}, $template);
		$template= preg_replace_callback("/\.*kc:!(.*):!/",function($match){return constant($match[1]) === null ? $match[1] : constant($match[1]);}, $template);
		$template= preg_replace_callback("/\.*ko:!(.*):!/",function($match) use ($option) {return !isset($option[$match[1]]) ? $match[1] : $option[$match[1]];}, $template);
		$template= preg_replace_callback("/\.*kod:!(.*):!/",function($match) use ($option) {return !isset($option[$match[1]]) ? "" : $option[$match[1]];}, $template);
		$template= preg_replace_callback("/\.*ktp:!(.*):!/",function($match){if($this->_templates === null){$this->load();}return isset($this->_templates[$match[1]]) ? $this->_templates[$match[1]] : $match[1];	}, $template);
		return $template;
	}
}