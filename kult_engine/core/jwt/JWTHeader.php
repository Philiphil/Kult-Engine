<?php

namespace KultEngine;

class JWTHeader{
	use JsonableTrait;
	public string $typ="JWT";
	public string $alg= "none";
}
