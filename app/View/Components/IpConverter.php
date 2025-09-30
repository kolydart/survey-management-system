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

        // Check if it's already a valid IP address (IPv4 or IPv6)
        if (filter_var($hex, FILTER_VALIDATE_IP)) {
            return $hex;
        }

        // Try to convert hex to IPv4
        // hexdec can return float for large values, so cast to int
        $decimal = hexdec($hex);

        // Check if it's a valid IPv4 range (0 to 4294967295)
        if ($decimal <= 4294967295) {
            return long2ip((int) $decimal);
        }

        // If conversion fails or is out of range, return original value
        return $hex;
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