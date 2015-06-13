<?php

class Ini {
  
  var $_INI_DATA = array ();
  var $_INI_DATA_FILES = array ();
  var $_CRLF = "\n";
  var $_WAIT = 10;

  function connect($file){
    if(!file_exists($file)) return -1;
    
    for($i=0;$i<sizeof($this->_INI_DATA_FILES);$i++){
      if($this->_INI_DATA_FILES[$i]==$file) return $i;
    }
    $ini_array = $this->_parse_ini_file($file, true);
    $this->_INI_DATA[] = $ini_array;
    $this->_INI_DATA_FILES[] = $file;
    return (sizeof($this->_INI_DATA)-1);
  }

  function fetch($link=''){
    $data_string = "";
    if(empty($link)) $link = sizeof($this->_INI_DATA)-1;
    if(sizeof($this->_INI_DATA)==0) return false;
    $get_data_from_array = $this->_INI_DATA[$link];
    $array_keys = array_keys($get_data_from_array);
    for($i=0;$i<sizeof($get_data_from_array);$i++){
      $get_data_from_key = $get_data_from_array[$array_keys[$i]];
      $key_array_keys = array_keys($get_data_from_key);
      $data_string .= "[" . $array_keys[$i] . "]" . $this->_CRLF;
      for($j=0;$j<sizeof($key_array_keys);$j++) $data_string .= $key_array_keys[$j].'='.$get_data_from_key[$key_array_keys[$j]].$this->_CRLF;
    }
    $filename = $this->_INI_DATA_FILES[$link];
    if(!file_exists($filename)) return false;
    if(!is_writable($filename)) return false;
    $fp = fopen($filename, "w+");
    fwrite($fp,"; <?php exit(\"<code>Permission denied</code>\"); ?>
; This is main configuration file
; Comments start with ';'
; DON'T DELETE THE FIRST LINE FOR SECURITY REASONS
;
");
    fwrite($fp, $data_string);
    fclose($fp);
    return true;
  }
  
  function close($link=''){
    $bool = $this->fetch($link);
    unset($this->_INI_DATA[$link]);
    unset($this->_INI_DATA_FILES[$link]);
    return $bool;
  }

  function drop_key($section, $key, $link){
    if(empty($link)) $link = sizeof($this->_INI_DATA)-1;
    if(sizeof($this->_INI_DATA)==0 || !$this->section_exists($section) || !$this->key_exists($section, $key)) return false;
    unset($this->_INI_DATA[$link][$section][$key]);
    return true;
  }

  function drop_section($section, $link){
    if(empty($link)) $link = sizeof($this->_INI_DATA)-1;
    if(sizeof($this->_INI_DATA)==0 || !$this->section_exists($section)) return false;
    unset($this->_INI_DATA[$link][$section]);
    return true;
  }

  function get_keys($section, $link=''){
    if(empty($link)) $link = sizeof($this->_INI_DATA)-1;
    if(sizeof($this->_INI_DATA)==0 || !$this->section_exists($section, $link)) return false;
    $get_data = $this->_INI_DATA[$link][$section];
    return array_keys($get_data);
  }

  function get_sections($link=''){
    if(empty($link)) $link = sizeof($this->_INI_DATA)-1;
    if(sizeof($this->_INI_DATA)==0) return false;
    $get_data = $this->_INI_DATA[$link];
    return array_keys($get_data);
  }

  function key_exists($section, $key, $link=''){
    if(empty($link)) $link = sizeof($this->_INI_DATA)-1;
    if(sizeof($this->_INI_DATA)==0 || !$this->section_exists($section, $link)) return false;
    $keys = $this->get_keys($section, $link);
    for($i=0;$i<sizeof($keys);$i++){
      if($keys[$i]==$key) return true;
    }
    return false;
  }

  function section_exists($section, $link=''){
    if(empty($link)) $link = sizeof($this->_INI_DATA)-1;
    if(sizeof($this->_INI_DATA)==0) return false;
    $sections = $this->get_sections($link);
    for($i=0;$i<sizeof($sections);$i++){
      if($sections[$i]==$section) return true;
    }
    return false;
  }

  function read($section, $key, $link=''){
    if(empty($link)) $link = sizeof($this->_INI_DATA)-1;
    if(sizeof($this->_INI_DATA)==0 || !$this->section_exists($section, $link) || !$this->key_exists($section, $key, $link)) return false;
    return $this->_INI_DATA[$link][$section][$key];
  }

  function write($section, $key='', $value='', $link=''){
    if(empty($link)) $link = sizeof($this->_INI_DATA) - 1;
    if(sizeof($this->_INI_DATA)==0 || empty($section)) return false;
    if(!$this->section_exists($section)) $this->_INI_DATA[$link][$section] = array();
    if(!empty($key)) $this->_INI_DATA[$link][$section][$key] = $value;
    return true;
  }

  function increase($section, $key, $link='',$v){
    $this->read($section, $key, $link);
    $this->write($section, $key, (integer) ($this->read($section, $key, $link) + $v), $link);
    return true;
  }

  function decrease($section, $key, $link='',$v){
    $this->read($section, $key, $link);
    $this->write($section, $key, (integer) ($this->read($section, $key, $link)-$v), $link);
    return true;
  }

  function _parse_ini_file($filename, $process_sections=false){
    $ini_array = array();
    $sec_name = '';
    $lines = file($filename);
    foreach($lines as $line){
      $line = trim($line);
      if($line=='' or substr($line,0,1)==';') continue;
      if($line[0]=="[" && $line[strlen($line)-1]=="]") $sec_name = substr($line, 1, strlen($line)-2);
      else{
        $pos = strpos($line, '=');
        $property = trim(substr($line, 0, $pos));
        $value = trim(substr($line, $pos+1));
        if($process_sections) $ini_array[$sec_name][$property] = $value;
        else $ini_array[$property] = $value;
      }
    }
    return $ini_array;
  }

}

?>