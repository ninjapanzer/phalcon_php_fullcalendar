<?php
namespace App\Assets;

class ApplicationManifest
{
  const JS_PATH  = '../app/assets/js/';
  const CSS_PATH = '../app/assets/css/';
  const MANIFEST_EXTENSION = '.manifest';

  protected $assets;
  protected $jsPath;
  protected $cssPath;
  protected $manifestExt;

  public function __construct($assets, $options = [])
  {
    $this->assets = $assets;
    $this->jsPath = $options['jsPath'] ?: JS_PATH;
    $this->cssPath = $options['cssPath'] ?: CSS_PATH;
    $this->manifestExt = $options['manifestExt'] ?: MANIFEST_EXTENSION;
  }

  public function loadManifests()
  {
    $this->loadJSManifests();
    $this->loadCSSManifests();
  }

  private function manifestGlob($assetsPath)
  {
    $cwd = getcwd();
    return glob("${cwd}/{$assetsPath}*{$this->manifestExt}");
  }

  private function loadManifest($type, $manifests, \Closure $callback)
  {
    foreach ($manifests as $manifest) {
      $collectionName = pathinfo($manifest)['filename'].$type;
      $collection = $this->assets->collection($collectionName);
      $includes = file($manifest, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

      $callback($collection, $includes, $manifest, $type);

      $collection->join(true)
        ->setTargetPath("../public/{$collectionName}.{$type}")
        ->setTargetUri("{$collectionName}.{$type}");
    }
  }

  private function loadJSManifests()
  {
    $manifests = $this->manifestGlob($this->jsPath);
    $this->loadManifest('js', $manifests, function (&$collection, $includes) {
      foreach ($includes as $include) {
        $collection->addJs($include);
      }

      $collection->addFilter(new \Phalcon\Assets\Filters\Jsmin());
    });
  }

  private function loadCSSManifests()
  {
    $manifests = $this->manifestGlob($this->cssPath);
    $this->loadManifest('css', $manifests, function (&$collection, $includes) {
      foreach ($includes as $include) {
        $collection->addCss($include);
      }

      $collection->addFilter(new \Phalcon\Assets\Filters\Cssmin());
    });
  }
}
