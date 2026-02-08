<?php

namespace Alphonse243\BioCycle\Exception;

class CycleIrregulierException extends \Exception
{
    public function __construct(string $message = "Cycle irrégulier détecté", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
