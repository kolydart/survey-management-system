<?php

namespace App\View\Components;

use Illuminate\View\Component;

class IpConverter extends Component
{
    public $hex;
    public $ip;

    /**
     * Create a new component instance.
     *
     * @param string $hex The hex value to convert
     */
    public function __construct($hex)
    {
        $this->hex = $hex;
        $this->ip = $this->convertHex2Ip($hex);
    }

    /**
     * Convert hex to IP address
     *
     * @param string $hex
     * @return string
     */
    private function convertHex2Ip($hex)
    {
        if (empty($hex)) {
            return '';
        }
        
        // Simple implementation - convert hex to IP
        return long2ip(hexdec($hex));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.ip-converter');
    }
}