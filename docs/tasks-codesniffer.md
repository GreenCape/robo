# CodeSniffer

## `CSDocument`

Creates a template based documentation for a ruleset.

See https://github.com/GreenCape/robo/tree/master/docs/codestyle-template.md
for information about how to build a custom template.

``` php
<?php
$this->taskCSDocument()
     ->standard('PEAR')
     ->template('tmpl/codestyle.html')
     ->outfile('docs/codestyle.html')
     ->run();
```

  - `standard($standard)` - [Path to] the standard to be documented
  - `template($template)` - Path to a Twig template to use for rendering
  - `outfile($outfile)` - Path to the output file
