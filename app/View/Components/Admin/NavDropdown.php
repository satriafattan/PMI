<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NavDropdown extends Component
{
    public string $icon;
    public string $label;
    public array $items;

    public function __construct(string $icon = '', string $label = '', array $items = [])
    {
        $this->icon = $icon;
        $this->label = $label;
        $this->items = $items;
    }

    public function render(): View|Closure|string
    {
        return view('components.admin.nav-dropdown');
    }

    public function isActive(): bool
    {
        foreach ($this->items as $item) {
            if (request()->routeIs($item['route'])) {
                return true;
            }
        }
        return false;
    }
}
