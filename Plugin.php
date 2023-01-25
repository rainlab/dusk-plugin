<?php namespace RainLab\Dusk;

use System\Classes\PluginBase;

/**
 * Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'October Dusk',
            'description' => 'Browser testing for October CMS, powered by Laravel Dusk.',
            'author' => 'Alexey Bobkov, Samuel Georges',
            'icon' => 'icon-chrome',
            'homepage' => 'https://github.com/rainlab/dusk-plugin',
        ];
    }

    /**
     * register the service provider
     */
    public function register()
    {
        $this->registerConsoleCommand('test.dusk', \RainLab\Dusk\Console\Dusk::class);
    }
}
