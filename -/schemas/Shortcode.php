<?php namespace std\shortcodes\schemas;

class Shortcode extends \Schema
{
    public $table = 'std_shortcodes';

    public function blueprint()
    {
        return function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->binary('code')->default('');
            $table->string('code_varchar')->default('');
            $table->text('calls');
            $table->dateTime('expires');
            $table->boolean('onetime')->default(false);
        };
    }
}
