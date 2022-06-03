<?php

namespace Mailery\Web\Widget;

use Yiisoft\Widget\Widget;

class SizeUnitsFormat extends Widget
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
        if ($this->bytes >= 1073741824) {
            return number_format($this->bytes / 1073741824, 2) . ' GB';
        } elseif ($this->bytes >= 1048576) {
            return number_format($this->bytes / 1048576, 2) . ' MB';
        } elseif ($this->bytes >= 1024) {
            return number_format($this->bytes / 1024, 2) . ' KB';
        } elseif ($this->bytes > 1) {
            return $this->bytes . ' bytes';
        } elseif ($this->bytes == 1) {
            return $this->bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }

}
