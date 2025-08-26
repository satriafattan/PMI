<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NavItem extends Component
{
    public string $route;
    public string $icon;
    public function __construct(string $route, string $icon='')
    {
        $this->route = $route;
        $this->icon  = $icon;
    }
    public function render(): View|Closure|string
    {
        return view('components.admin.nav-item');
    }
}
