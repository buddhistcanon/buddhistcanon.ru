<?php

namespace App\Data;

use App\Exceptions\Domain\WrongSuttaNameException;
use Spatie\LaravelData\Data;

class SuttaNameData extends Data
{
    public function __construct(
        public string $category,
        public string $order,
        public ?string $suborder
    ) {
    }

    public static function fromString(string $string): self
    {
        $string = strtolower($string);
        preg_match('/^(dn|mn|an|sn)/', $string, $match);
        if (! isset($match[1])) {
            throw new WrongSuttaNameException("$string is not valid sutta name");
        }
        $category = $match[1];

        $orders = str_replace($category, '', $string);
        if (str_contains($orders, '.')) {
            $array = explode('.', $orders);
            $order = $array[0];
            $suborder = $array[1];
        } else {
            $order = $orders;
            $suborder = null;
        }
        if (! $orders) {
            throw new WrongSuttaNameException("$string is not valid sutta name");
        }

        return new self($category, $order, $suborder);
    }

    public function name(): string
    {
        $name = $this->category.$this->order;
        if ($this->suborder) {
            $name .= '.'.$this->suborder;
        }

        return $name;
    }
}
