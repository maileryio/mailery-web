<?php

namespace Mailery\Web\Widget;

use Yiisoft\Widget\Widget;

class ByteUnitsFormat extends Widget
{

    /**
     * @var int
     */
    private int $bytes;

    /**
     * @param int $bytes
     * @return self
     */
    public function bytes(int $bytes): self
    {
        $new = clone $this;
        $new->bytes = $bytes;

        return $new;
    }

    /**
     * @param string $string
     * @return self
     */
    public function string(string $string): self
    {
        $new = clone $this;
        $new->bytes = strlen($string);

        return $new;
    }

    /**
     * @return string
     */
    public function run(): string
    {
        return \ByteUnits\bytes($this->bytes)->format(null, ' ');
    }

}
