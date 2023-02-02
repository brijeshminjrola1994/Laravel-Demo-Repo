<?php

namespace App\View\Components;

use App\Models\Company;
use App\Models\GlobalSetting;
use App\Models\User;
use Illuminate\View\Component;

class Auth extends Component
{

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $globalSetting = GlobalSetting::first();

        return view('components.auth', ['globalSetting' => $globalSetting]);
    }

}
