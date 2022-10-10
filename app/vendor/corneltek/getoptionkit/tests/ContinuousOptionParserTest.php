<?php
/*
 * This file is part of the {{ }} package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace tests\GetOptionKit;
use GetOptionKit\ContinuousOptionParser;
use GetOptionKit\OptionCollection;

class ContinuousOptionParserTest extends \PHPUnit\Framework\TestCase
{

    public function testOptionCollection()
    {
        $specs = new OptionCollection;
        $specVerbose = $specs->add('v|verbose');
        $specColor = $specs->add('c|color');
        $specDebug = $specs->add('d|debug');
    }



    public function argumentProvider()
    {
        return [
            [
                ['program','subcommand1', 'arg1', 'arg2', 'arg3', 'subcommand2', '-b', 1, 'subcommand3', '-b', 2],
                [
                    'args' => ['arg1', 'arg2', 'arg3']
                ],
            ],
            [
                ['program','-v', '-c', 'subcommand1', '--as', 99, 'arg1', 'arg2', 'arg3'],
                [
                    'app' => ['verbose' => true ],
                    'args' => ['arg1', 'arg2', 'arg3']
                ],
            ],
            [
                ['program','-v', '-c', 'subcommand1', '--as', 99, 'arg1', 'arg2', 'arg3', '--','zz','xx','vv'],
                [
                    'app' => ['verbose' => true],
                    'args' => ['arg1', 'arg2', 'arg3']
                ],
            ],
        ];
    }



    /**
     * @dataProvider argumentProvider
     */
    public function testParseSubCommandOptions($argv, $expected)
    {
        $appspecs = new OptionCollection;
        $appspecs->add('v|verbose');
        $appspecs->add('c|color');
        $appspecs->add('d|debug');

        $cmdspecs = new OptionCollection;
        $cmdspecs->add('as:');
        $cmdspecs->add('b:');
        $cmdspecs->add('c:');
        $cmdspecs->add('def:')->isa('number')->defaultValue(3);

        $parser = new ContinuousOptionParser( $appspecs );

        $subcommands = array('subcommand1','subcommand2','subcommand3');
        $subcommand_specs = array(
            'subcommand1' => clone $cmdspecs,
            'subcommand2' => clone $cmdspecs,
            'subcommand3' => clone $cmdspecs,
        );
        $subcommand_options = array();

        // $argv = explode(' ','program -v -c subcommand1 --as 99 arg1 arg2 arg3 -- zz xx vv');
        // $argv = explode(' ','program subcommand1 -a 1 subcommand2 -a 2 subcommand3 -a 3 arg1 arg2 arg3');
        $app_options = $parser->parse( $argv );
        $arguments = array();
        while (! $parser->isEnd()) {
            if (!empty($subcommands) && $parser->getCurrentArgument() == $subcommands[0]) {
                $parser->advance();
                $subcommand = array_shift($subcommands);
                $parser->setSpecs($subcommand_specs[$subcommand]);
                $subcommand_options[$subcommand] = $parser->continueParse();
            } else {
                $arguments[] = $parser->advance();
            }
        }
        $this->assertSame($expected['args'], $arguments);
        if (isset($expected['app'])) {
            foreach ($expected['app'] as $k => $v) {
                $this->assertEquals($v, $app_options->get($k));
            }
        }

        // $this->assertEquals(99, $subcommand_options['subcommand1']->as);
    }



    public function testParser3()
    {
        $appspecs = new OptionCollection;
        $appspecs->add('v|verbose');
        $appspecs->add('c|color');
        $appspecs->add('d|debug');

        $cmdspecs = new OptionCollection;
        $cmdspecs->add('n|name:=string');
        $cmdspecs->add('p|phone:=string');
        $cmdspecs->add('a|address:=string');


        $subcommands = array('subcommand1','subcommand2','subcommand3');
        $subcommand_specs = array(
            'subcommand1' => $cmdspecs,
            'subcommand2' => $cmdspecs,
            'subcommand3' => $cmdspecs,
        );
        $subcommand_options = array();
        $arguments = array();

        $argv = explode(' ','program -v -d -c subcommand1 --name=c9s --phone=123123123 --address=somewhere arg1 arg2 arg3');
        $parser = new ContinuousOptionParser( $appspecs );
        $app_options = $parser->parse( $argv );
        while (! $parser->isEnd()) {
            if (@$subcommands[0] && $parser->getCurrentArgument() == $subcommands[0]) {
                $parser->advance();
                $subcommand = array_shift( $subcommands );
                $parser->setSpecs( $subcommand_specs[$subcommand] );
                $subcommand_options[ $subcommand ] = $parser->continueParse();
            } else {
                $arguments[] = $parser->advance();
            }
        }

        $this->assertCount(3, $arguments);
        $this->assertEquals('arg1', $arguments[0]);
        $this->assertEquals('arg2', $arguments[1]);
        $this->assertEquals('arg3', $arguments[2]);

        $this->assertNotNull($subcommand_options['subcommand1']);
        $this->assertEquals('c9s', $subcommand_options['subcommand1']->name );
        $this->assertEquals('123123123', $subcommand_options['subcommand1']->phone );
        $this->assertEquals('somewhere', $subcommand_options['subcommand1']->address );
    }


    /* test parser without options */
    function testParser4()
    {
        $appspecs = new OptionCollection;
        $appspecs->add('v|verbose');
        $appspecs->add('c|color');
        $appspecs->add('d|debug');

        $cmdspecs = new OptionCollection;
        $cmdspecs->add('a:'); // required
        $cmdspecs->add('b?'); // optional
        $cmdspecs->add('c+'); // multiple (required)



        $parser = new ContinuousOptionParser( $appspecs );
        $this->assertNotNull( $parser );

        $subcommands = array('subcommand1','subcommand2','subcommand3');
        $subcommand_specs = array(
            'subcommand1' => clone $cmdspecs,
            'subcommand2' => clone $cmdspecs,
            'subcommand3' => clone $cmdspecs,
        );
        $subcommand_options = array();

        $argv = explode(' ','program subcommand1 subcommand2 subcommand3 -a a -b b -c c');
        $app_options = $parser->parse( $argv );
        $arguments = array();
        while( ! $parser->isEnd() ) {
            if( @$subcommands[0] && $parser->getCurrentArgument() == $subcommands[0] ) {
                $parser->advance();
                $subcommand = array_shift( $subcommands );
                $parser->setSpecs( $subcommand_specs[$subcommand] );
                $subcommand_options[ $subcommand ] = $parser->continueParse();
            } else {
                $arguments[] = $parser->advance();
            }
        }

        $this->assertNotNull( $subcommand_options );
        $this->assertNotNull( $subcommand_options['subcommand1'] );
        $this->assertNotNull( $subcommand_options['subcommand2'] );
        $this->assertNotNull( $subcommand_options['subcommand3'] );

        $r = $subcommand_options['subcommand3'];
        $this->assertNotNull( $r );


        
        $this->assertNotNull( $r->a , 'option a' );
        $this->assertNotNull( $r->b , 'option b' );
        $this->assertNotNull( $r->c , 'option c' );

        $this->assertEquals( 'a', $r->a );
        $this->assertEquals( 'b', $r->b );
        $this->assertEquals( 'c', $r->c[0] );
    }

    /* test parser without options */
    function testParser5()
    {
        $appspecs = new OptionCollection;
        $appspecs->add('v|verbose');
        $appspecs->add('c|color');
        $appspecs->add('d|debug');

        $cmdspecs = new OptionCollection;
        $cmdspecs->add('a:');
        $cmdspecs->add('b');
        $cmdspecs->add('c');

        $parser = new ContinuousOptionParser( $appspecs );
        $this->assertNotNull( $parser );

        $subcommands = array('subcommand1','subcommand2','subcommand3');
        $subcommand_specs = array(
            'subcommand1' => clone $cmdspecs,
            'subcommand2' => clone $cmdspecs,
            'subcommand3' => clone $cmdspecs,
        );
        $subcommand_options = array();

        $argv = explode(' ','program subcommand1 -a 1 subcommand2 -a 2 subcommand3 -a 3 arg1 arg2 arg3');
        $app_options = $parser->parse( $argv );
        $arguments = array();
        while (! $parser->isEnd()) {
            if (!empty($subcommands) && $parser->getCurrentArgument() == $subcommands[0] ) {
                $parser->advance();
                $subcommand = array_shift( $subcommands );
                $parser->setSpecs($subcommand_specs[$subcommand]);
                $subcommand_options[ $subcommand ] = $parser->continueParse();
            } else {
                $arguments[] = $parser->advance();
            }
        }
        
        $this->assertEquals( 'arg1', $arguments[0] );
        $this->assertEquals( 'arg2', $arguments[1] );
        $this->assertEquals( 'arg3', $arguments[2] );
        $this->assertNotNull( $subcommand_options );

        $this->assertEquals(1, $subcommand_options['subcommand1']->a);
        $this->assertNotNull( 2, $subcommand_options['subcommand2']->a );
        $this->assertNotNull( 3, $subcommand_options['subcommand3']->a );
    }

    /**
     * @expectedException GetOptionKit\Exception\InvalidOptionException
     */
    public function testParseInvalidOptionException()
    {
        $parser = new ContinuousOptionParser(new OptionCollection);
        $parser->parse(array('app','--foo'));
        $arguments = array();
        while (!$parser->isEnd())
        {
            $arguments[] = $parser->getCurrentArgument();
            $parser->advance();
        }
    }



    public function testMultipleShortOption()
    {
        $options = new OptionCollection;
        $options->add("a");
        $options->add("b");
        $options->add("c");

        $parser = new ContinuousOptionParser($options);

        $result = $parser->parse(array('app', '-ab', 'foo', 'bar'));
        while (!$parser->isEnd())
        {
            $arguments[] = $parser->getCurrentArgument();
            $parser->advance();
        }

        $this->assertTrue($result->keys["a"]->value);
        $this->assertTrue($result->keys["b"]->value);
    }

    public function testIncrementalValue()
    {
        $options = new OptionCollection;
        $options->add("v|verbose")->incremental();
        $parser = new ContinuousOptionParser($options);
        $result = $parser->parse(array('app', '-vvv'));
        $this->assertEquals(3, $result->keys["verbose"]->value);
    }


    /**
     * @expectedException GetOptionKit\Exception\InvalidOptionException
     */
    public function testUnknownOption()
    {
        $options = new OptionCollection;
        $options->add("v|verbose");
        $parser = new ContinuousOptionParser($options);
        $result = $parser->parse(array('app', '-b'));
    }

    /**
     * @expectedException LogicException
     */
    public function testAdvancedOutOfBounds()
    {
        $options = new OptionCollection;
        $options->add("v|verbose");
        $parser = new ContinuousOptionParser($options);
        $result = $parser->parse(array('app', '-v'));
        $parser->advance();
    }

}

