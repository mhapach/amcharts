<?php

namespace mhapach\AmCharts\Libs;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

/**
 * Base class for amChart PHP-Library
 */
class AmChart
{
    /** @var array - chart config */
    protected array $config = [];
    /** @var array - layout params like width or height of div */
    protected array $layoutSettings = [];
    protected array $data = [];
    protected array $libraries = [];
    protected string $chartType;

    /**
     * Constructor can only be called from derived class because AmChart
     * is abstract.
     *
     * @param string $id
     * @param string $width
     * @param string $height
     */
    public function __construct(string $chartType, string $id = null, string $width = "100%", string $height = "500px")
    {
        if (!$id)
            $id = substr(md5(uniqid() . microtime()), 3, 5);

        $this->chartType = $chartType;
        $this->layoutSettings['id'] = $id;
        $this->layoutSettings['width'] = $width;
        $this->layoutSettings['height'] = $height;
    }

    /**
     * Add a title to the chart
     *
     * @param string $text
     * @param string $color
     * @param int $size
     * @param string $id HTML-ID of the title
     * @param int $alpha
     * @return  void
     */
    public function addTitle($text, $color = "", $size = 14, $id = "chart-title", $alpha = 1): void
    {
        $this->layoutSettings["titles"][] = array(
            "text" => $text,
            "color" => $color,
            "size" => $size,
            "id" => $id,
            "alpha" => $alpha
        );
    }

    /**
     * Sets the config array. It should look like this:
     * array(
     *   "width" => "300px",
     *   "height" => "100px",
     * )
     *
     * @param array $config
     * @param bool $merge
     */
    public function setConfig(array $config = [], $merge = false): void
    {
        if ($merge) {
            $this->config = array_merge($this->config, $config);
        } else {
            $this->config = $config;
        }
    }

    /**
     * Sets config variable with full path
     * Example
     *   $array = ['products' => ['desk' => ['price' => 100]]];
     *   Arr::set($array, 'products.desk.price', 200);
     * https://laravel.com/docs/9.x/helpers#method-array-set.
     *
     * @param string $key
     * @param mixed $value
     */
    public function setConfigValue($key, $value): void
    {
        Arr::set($this->config, $key, $value);
    }

    /**
     * Returns the config array.
     *
     * @return    array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    public function setLibraries(array $libs): void
    {
        $this->libraries = $libs;
    }

    /**
     * @return  string[]
     */
    public function getLibraries(): array
    {
        return $this->libraries;
    }

    public function appendLibrary(string $lib): void
    {
        $this->libraries[] = $lib;
    }

    /**
     * @see "AmChart"=>:getData()
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @see     "AmChart"=>:setData()
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * Returns the HTML Code to insert on the page.
     *
     * @return    string
     */
    public function render(): string {
        return view("amcharts::$this->chartType", [
            'libraries' => $this->libraries,
            'layoutSettings' => $this->layoutSettings,
            'config' => $this->getConfig(),
            'data' => $this->getData()
        ])->render();
    }

}
