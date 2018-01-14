<?php

namespace spec\MKolecki\Behat\FixturesExtension;

use PhpSpec\ObjectBehavior;

class FixturesSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith($this->getSampleData());
    }

    function getSampleData()
    {
        return array(
            'foo' => array(
                'bar' => array(
                    'abra' => 'cadabra',
                ),
                'baz' => array(
                    'boom' => 12345,
                ),
            ),
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MKolecki\Behat\FixturesExtension\Fixtures');
    }

    function it_allow_fetch_data_by_key()
    {
        $this->beConstructedWith(array(
            'foo' => 'bar',
        ));

        $this->get('foo')->shouldReturn('bar');
    }

    function it_allow_fetch_nested_data_by_path()
    {
        $this->get('foo/bar/abra')->shouldReturn('cadabra');
    }

    function it_allow_to_set_delimiter()
    {
        $this->setDelimiter('.');

        $this->get('foo.bar.abra')->shouldReturn('cadabra');
        $this->get('foo.baz.boom')->shouldReturn(12345);
    }

    function it_allow_to_use_array_access()
    {
        $this['foo/bar/abra']->shouldReturn('cadabra');
        $this['foo']['bar']['abra']->shouldReturn('cadabra');
        $this['foo']['bar/abra']->shouldReturn('cadabra');
    }

    function it_return_fixtures_object_when_path_is_not_for_leaf()
    {
        $this->get('foo')->shouldReturnAnInstanceOf('MKolecki\\Behat\\FixturesExtension\\Fixtures');
        $this->get('foo')->get('bar/abra')->shouldReturn('cadabra');
    }

    function it_return_fixtures_object_when_accessing_array_index_when_not_leaf()
    {
        $this['foo']->shouldReturnAnInstanceOf('MKolecki\\Behat\\FixturesExtension\\Fixtures');
        $this['foo']->get('bar/abra')->shouldReturn('cadabra');
    }

    function it_throws_exception_when_path_do_not_exist()
    {
        $this->shouldThrow('InvalidArgumentException')
            ->during('get', array('foo/asasa'));

        $this->shouldThrow('InvalidArgumentException')
            ->during('get', array('foo/bar/dsds'));
    }
}
