<?php namespace std\shortcodes;

class Shortcode
{
    private $length = 5;

    /**
     * @var \Carbon\Carbon
     */
    private $expires = false;

    private $onetime = false;

    private $calls = [];

    private $path;

    private $data = [];

    private $controller;

    /**
     * Shortcode constructor.
     *
     * @param \ewma\Controllers\Controller|false $controller
     */
    public function __construct($controller = false)
    {
        $this->controller = $controller ?: appc();
    }

    public function addCall($path, $data = [])
    {
        $this->calls[] = $this->controller->_abs($path, $data);

        return $this;
    }

    public function onetime()
    {
        $this->onetime = true;

        return $this;
    }

    public function length($length)
    {
        $this->length = $length;

        return $this;
    }

    public function expires($datetime)
    {
        $this->expires = \Carbon\Carbon::parse($datetime);

        return $this;
    }

    public function ttl($ttl)
    {
        $this->expires = \Carbon\Carbon::now()->addSeconds($ttl);

        return $this;
    }

    public function path($path)
    {
        $this->path = $path;

        return $this;
    }

    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    public function create()
    {
        do {
            $code = h($this->length);
        } while (\std\shortcodes\models\Shortcode::where('code', $code)->first()); // todo binary (?)

        \std\shortcodes\models\Shortcode::create([
                                                     'code'         => $code,
                                                     'code_varchar' => $code,
                                                     'calls'        => j_($this->calls),
                                                     //                                                     'path'         => $this->path,
                                                     //                                                     'data'         => j_($this->data),
                                                     'expires'      => $this->expires->toDateTimeString(),
                                                     'onetime'      => $this->onetime
                                                 ]);

        return $code;
    }

    public static function handle($code)
    {
        if ($shortcode = \std\shortcodes\models\Shortcode::where('code', $code)->first()) { // todo binary (?)
            $calls = _j($shortcode->calls) ?: [];

            $appc = appc();

            foreach ($calls as $call) {
                $appc->_call($call)->perform();
            }

            if ($shortcode->onetime) {
                $shortcode->delete();
            }

            return true;
        }
    }
}
