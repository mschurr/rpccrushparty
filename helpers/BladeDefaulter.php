<?php

class BladeDefaulter implements ArrayAccess {
  protected $structures;

  public function __construct(/*array<ArrayAccess|array>*/ $structures) {
    $this->structures = $structures;
  }

  public function selected($k, $value) {
    if(isset($this[$k]) && $this[$k] == $value)
      return ' selected="selected"';

    return '';
  }

  public function checked($k, $value = null) {
    if ($value !== null) {
      return isset($this[$k]) && $this[$k] == $value ? ' checked="checked"' : '';
    }

    return isset($this[$k]) ? ' checked="checked"' : '';
  }

  public function get($k, $default = '') {
    $k = str_replace("-","_",$k);

    foreach ($this->structures as $s) {
      if (isset($s[$k])) {
        return $s[$k];
      }
    }

    return $default;
  }

  public function offsetGet($k) {
    $k = str_replace("-","_",$k);

    foreach ($this->structures as $s) {
      if (isset($s[$k])) {
        return $s[$k];
      }
    }

    throw new BadAccessException($k);
  }

  public function offsetSet($offset, $value) {
    throw new ImmutableObjectException;
  }

  public function offsetExists($k) {
    $k = str_replace("-","_",$k);

    foreach ($this->structures as $s) {
      if (isset($s[$k])) {
        return true;
      }
    }

    return false;
  }

  public function offsetUnset($offset) {
    throw new ImmutableObjectException;
  }
}
