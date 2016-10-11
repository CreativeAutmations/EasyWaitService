<?php namespace App\Console\Commands;

use Illuminate\Foundation\Console\TinkerCommand as BaseTinker;
use Illuminate\Foundation\Console\Tinker\Presenters\IlluminateCollectionPresenter;
use Illuminate\Foundation\Console\Tinker\Presenters\IlluminateApplicationPresenter;

class TinkerCommand extends BaseTinker {

    /**
     * Get an array of Laravel tailored Presenters.
     *
     * @return array
     */
    protected function getPresenters()
    {
        return [
            new IlluminateCollectionPresenter,
            new IlluminateApplicationPresenter,
        ];
    }
}

