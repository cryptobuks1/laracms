<?php

namespace Laracms\Singletons;

use Route;

class Menu
{
    /**
     * Define list of menu
     *
     * @var array
     */
    private $menu;

    /**
     * Create a new service provider instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->menu = config('laracms.menu');
    }

    /**
     * Render menu
     * 
     * @return array
     */
    public function render()
    {
        # return if $menu null
        if (empty($this->menu)) {
            return '';
        }
        # Define html render variable
        $html = '';
        # Loop menu
        foreach ($this->menu as $menu) {
            switch (@$menu['type']) {
                # Type heder
                case 'header':
                    $html .= '<li class="nav-header">'.@$menu["label"].'</li>';
                    break;
                # Type item
                case 'item':
                    $subs = @$menu['sub'];
                    $route = Route::has(@$menu['route']) ? route(@$menu['route']) : '#';
                    if (empty($subs)) {
                        $html .= '<li class="nav-item">';
                    } else {
                        $html .= '<li class="nav-item has-treeview">';
                    }
                    $html .= '<a href="'.$route.'" class="nav-link">';
                    if (!empty(@$menu['icon'])) {
                        $html .= '<i class="nav-icon '.@$menu['icon'].'"></i>';
                    }
                    $html .= '<p>'.@$menu['label'];
                    if (!empty($subs)) {
                        $html .= '<i class="right fas fa-angle-left"></i>';
                    }
                    $html .= '</p></a>';
                    if (!empty($subs)) {
                        $html .= '<ul class="nav nav-treeview">';
                        foreach ($subs as $sub) {
                            $sub_route = Route::has(@$sub['route']) ? route(@$sub['route']) : '#';
                            $html .= '<li class="nav-item">
                                <a href="'.$sub_route.'" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>'.@$sub['label'].'</p>
                                </a>
                            </li>';
                        }
                        $html .= '</ul>';
                    }
                    $html .= '</li>';
                    break;
                # Default
                default:
                    break;
            }
        }
        return $html;
    }

}