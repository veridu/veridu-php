<?php
/**
* Abstract signature implementation
*/
namespace Veridu\Signature;

abstract class AbstractSignature implements SignatureInterface
{
    /**
    * @var string An arbitrary string, to identify the request.
    */
    protected $nonce = null;

    /**
    * {@inheritDoc}
    */
    public function lastNonce()
    {
        return $this->nonce;
    }
}
