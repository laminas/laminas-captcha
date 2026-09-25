<?php

declare(strict_types=1);

use Boundwize\StructArmed\Architecture;
use Boundwize\StructArmed\Preset\Preset;
use Boundwize\StructArmed\Rule\Rules\Class_\MustBeFinalRule;

return Architecture::define()
    ->withPresets(Preset::PSR4(), Preset::CODEQUALITY())
    ->layer('Exception', 'src/Exception')
    ->layer('Adapter', [
        'src/AdapterInterface.php',
        'src/AbstractAdapter.php',
    ])
    ->layer('Word', [
        'src/AbstractWord.php',
        'src/Dumb.php',
        'src/Figlet.php',
        'src/Image.php',
    ])
    ->layer('ReCaptcha', 'src/ReCaptcha.php')
    ->layer('Factory', 'src/Factory.php')
    ->layer('tests', 'test', 'test/TestAsset')
    ->ruleset([
        'Exception' => [],
        'Adapter'   => ['Exception'],
        'Word'      => ['+Adapter'],
        'ReCaptcha' => ['+Adapter'],
        'Factory'   => ['+Word', '+ReCaptcha'],
    ])
    ->rule(
        'tests_classes.must_be_final',
        new MustBeFinalRule(layer: 'tests')
    );
