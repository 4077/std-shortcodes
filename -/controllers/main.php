<?php namespace std\shortcodes\controllers;

class Main extends \Controller
{
    public function handle()
    {
        return \std\shortcodes\Shortcode::handle($this->data('code'));
    }
}
