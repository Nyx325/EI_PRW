<?php
class UserVisibleException extends Exception
{
  public function __construct(string $msg)
  {
    parent::__construct($msg);
  }
}
