<?php

namespace App\View\Components;

use Illuminate\View\Component;


class StatCard extends Component
{

    public function __construct(

        public string $title,

        public int $value

    ) {}



    public function render()
    {
        return view(
            'components.stat-card'
        );
    }

}