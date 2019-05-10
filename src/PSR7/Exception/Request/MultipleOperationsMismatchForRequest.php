<?php

declare(strict_types=1);

namespace OpenAPIValidation\PSR7\Exception\Request;

use Exception;
use OpenAPIValidation\PSR7\OperationAddress;
use function array_map;
use function implode;
use function sprintf;

class MultipleOperationsMismatchForRequest extends Exception
{
    /** @var OperationAddress[] */
    protected $matchedAddrs;

    /**
     * @param OperationAddress[] $addrs
     *
     * @return MultipleOperationsMismatchForRequest
     */
    public static function fromMatchedAddrs(array $addrs) : self
    {
        $addrsStrings = array_map(static function (OperationAddress $addr) {
            return sprintf('[%s,%s]', $addr->path(), $addr->method());
        }, $addrs);

        $message         = 'The given request matched these operations: %s. However, it matched not a single schema of theirs.';
        $i               = new self(sprintf($message, implode(',', $addrsStrings)));
        $i->matchedAddrs = $addrs;

        return $i;
    }

    /**
     * @return OperationAddress[]
     */
    public function matchedAddrs() : array
    {
        return $this->matchedAddrs;
    }
}
