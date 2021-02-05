<?php
require_once 'vendor/autoload.php';

use Garden\Cli\Cli;
use Wujunze\Colors;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFilter;

try {
  // Define the cli options.
  $cli = new Cli();

  $cli->description('Scaffold a new Nursery module')
      ->opt('name:n', 'Name of module', true)
      ->opt('singular:s', '(Optional) Name of singular module item', false)
      ->opt('plural:p', '(Optional) Name of singular module item', false)
      ->opt('attr:a', '(Optional) A comma-separated list of attributes to add to the model', false)
      ->opt('database:d', '(Optional) A database name for this module; creates database class', false)
      ->opt('transient:t', '(Optional) Create a transient script class for this module', false);

  // Parse and return cli args.
  $args = $cli->parse($argv, true);
  $attrs = $args->getOpt('attr', false);
  $params = [
    'name' => $args->getOpt('name'),
    'singular' => $args->getOpt('singular'),
    'plural' => $args->getOpt('plural'),
    'attrs' => $attrs ? array_map(function($attr) {
      $parts = explode('.', $attr);
      return [
        'name' => $parts[0],
        'type' => isset($parts[1]) ? $parts[1] : null
      ];
    }, explode(',', $attrs)) : null,
    'database' => $args->getOpt('database', false),
    'transient' => $args->getOpt('transient', false),
  ];

  $loader = new FilesystemLoader(__DIR__);
  $twig = new Environment($loader, [
      // 'cache' => __DIR__ . '/cache'
  ]);
  
  $twig->addFilter(new TwigFilter('lcfirst', 'lcfirst'));
  $twig->addFilter(new TwigFilter('pascalplain', function($input) {
    return ucfirst(trim(strtolower(preg_replace('/(?<!\ )[A-Z]/', ' $0', $input))));
  }));
  $twig->addFilter(new TwigFilter('snakepascal', function($input) {
    return str_replace('_', '', ucwords($input, '_'));
  }));

  function getCompiledPath($filepath) {
    return str_replace([DIRECTORY_SEPARATOR . 'templates','.twig'], [DIRECTORY_SEPARATOR . 'src','.php'], $filepath);
  }

  function directoryTree($path) {
    $templates = scandir(__DIR__ . $path);
    $dirs = array_reduce($templates, function($final, $file) use ($path) {
      $fullpath = $path . DIRECTORY_SEPARATOR . $file;
      if (substr($file, 0, 1) !== '.') {
        if (is_dir(__DIR__ . $fullpath)) {
          $final[$fullpath] = directoryTree($fullpath);
        } else {
          array_push($final, $fullpath);
        }
      }
      return $final;
    }, []);
    return $dirs;
  }

  function getOverwrites($dirs, Environment $renderer, array $params) {
    return array_reduce(array_keys($dirs), function($final, $key) use ($dirs, $renderer, $params) {
      $path = $dirs[$key];
      if (is_int($key)) {
        $pathtemplate = $renderer->createTemplate($path);
        $filepath = $renderer->render($pathtemplate, $params);
        $realpath = getCompiledPath($filepath);
        if (file_exists(__DIR__ . $realpath)) {
          array_push($final, $realpath);
        }
      }  else {
        $overwrites = (array) getOverwrites($path, $renderer, $params);
        array_push($final, ...$overwrites);
      }
      return $final;
    }, []);
  }

  function validateDirs($dirs, Environment $renderer, array $params) {
    $colors = new Colors();
    $overwrites = getOverwrites($dirs, $renderer, $params);
    if (count($overwrites)) {
      $list = $colors->getColoredString(implode("\n", $overwrites), "cyan");
      $warning = $colors->getColoredString("WARNING:", "red");
      echo "\n$warning This operation will overwrite the following files:\n\n$list\n\nContinue? (y/n)";
      $f=popen("read; echo \$REPLY","r");
      $input=fgets($f,100);
      pclose($f);
      if (strtolower(trim($input)) !== 'y') {
        $exit = $colors->getColoredString("\nCancelled rendering!\n", "yellow");
        exit($exit);
      }
    }
  }

  function renderTemplates(array $dirs, Environment $renderer, array $params) {
    $colors = new Colors();
    foreach (array_keys($dirs) as $key) {
      $path = $dirs[$key];
      if (is_int($key)) {
        $render = $renderer->render($path, $params);
        $pathtemplate = $renderer->createTemplate($path);
        $filepath = $renderer->render($pathtemplate, $params);
        $realpath = getCompiledPath($filepath);
        $fullpath = __DIR__ . $realpath;
        $directory = dirname($fullpath);
        if (!is_dir($directory)) {
          mkdir($directory, 0777, true);
        }
        file_put_contents($fullpath, $render);
        $file = $colors->getColoredString($realpath, "green");
        echo "$file\n";
      }
      else {
        renderTemplates($path, $renderer, $params);
      }
    }
  }

  $dirs = directoryTree('/templates');
  validateDirs($dirs, $twig, $params);
  echo "\nCreated:\n";
  renderTemplates($dirs, $twig, $params);
  
} catch (\Throwable $th) {
  echo $th->getMessage();
}