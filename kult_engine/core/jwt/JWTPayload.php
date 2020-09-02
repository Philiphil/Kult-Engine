<?php

namespace KultEngine;

class JWTPayload{
	use JsonableTrait;
	public $exp="";
	public $iat= "";
	public $nbf="";
	public $jti= "";
	public $iss="";
	public $sub= "";
	public $aud= "";
	public int $maxage= 3600;

	public function generateClaims(): self
	{
		$time = time();
		$this->iat = $time;
		$this->nbf = $time;
		$this->exp = $time+$this->maxage;
		$this->jti = uniqid();
		return $this;
	}

	public function verifyClaims():bool
	{
		if( $this->exp < time() ) throw new \Exception("expired");
		if( $this->iat > time() ) throw new \Exception("issued in the future");
		if( $this->nbf > time() ) throw new \Exception("used before");

		return true;
	}

}

/* ex
$d = new JWT();
$d->setAlg(JWT::ALG_HS256);
$e = $d->encode();
var_dump( JWT::decode($e) );
*/