<?php

namespace App\View\Components\Sidebar;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Links extends Component
{
    public string $title, $route, $icon, $active;
    public function __construct($title, $route, $icon)
    {
        $this->title = $title;
        $this->route = $route;
        $this->icon = $icon;
        $basePath = $this->generatePath($route);
        $this->active = request()->routeIs($basePath) ? 'bg-blue-300 text-white' : '';
    }

    public function generatePath($route)
    {
        if (str_contains($route, '.')) {
            $path = explode('.', $route);
            return $path[0] . '.*';
        } else {
            return $route;
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.sidebar.links');
    }
}
