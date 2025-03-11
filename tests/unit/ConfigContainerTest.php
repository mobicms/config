<?php

declare(strict_types=1);

use Mobicms\Config\ConfigContainer;
use Mobicms\Config\Exception\KeyAlreadyExistsException;

test('Has method', function () {
    $config = new ConfigContainer(['foo' => 'bar']);

    expect($config->has('foo'))->toBeTrue();
    expect($config->has('baz'))->toBeFalse();
});

test('Get method can return flat data', function () {
    $data = [
        'int'    => 12345,
        'string' => 'teststring',
        'array'  => ['test'],
        'bool'   => true,
    ];
    $config = new ConfigContainer($data);

    expect($config->get('int'))->toEqual(12345)
        ->and($config->get('string'))->toEqual('teststring')
        ->and($config->get('array'))->toEqual(['test'])
        ->and($config->get('bool'))->toBeTrue();
});

test('Get method can return nested array data', function () {
    $data = [
        'array' => [
            'foo'    => 'bar',
            'nested' => [
                'baz' => 'bat',
            ],
        ],
    ];
    $config = new ConfigContainer($data);

    expect($config->get(['array', 'foo']))->toBe('bar')
        ->and($config->get(['array', 'nested', 'baz']))->toBe('bat');
});

test('Get method can return default value', function () {
    $config = new ConfigContainer();

    expect($config->get('foo'))->toBeNull()
        ->and($config->get('foo', 'string'))->toEqual('string')
        ->and($config->get('foo', 12345))->toEqual(12345)
        ->and($config->get('foo', true))->toBeTrue()
        ->and($config->get(['foo', 'bar'], 'string'))->toEqual('string');
});

test('Set method can store data', function () {
    $config = new ConfigContainer();
    $config->set('string', 'test');
    $config->set('int', 12345);
    $config->set(
        'array',
        [
            'foo' => 'bar',
            'baz' => 'bat',
        ]
    );

    expect($config->get('string'))->toEqual('test')
        ->and($config->get('int'))->toEqual(12345)
        ->and($config->get('array'))->toEqual(['foo' => 'bar', 'baz' => 'bat']);
});

test('Unset method', function () {
    $config = new ConfigContainer(['foo' => 'bar']);
    expect($config->has('foo'))->toBeTrue();
    $config->unset('foo');
    expect($config->has('foo'))->toBeFalse();
});

describe('Exception handling:', function () {
    test('on existing key', function () {
        $config = new ConfigContainer(['foo' => 'bar']);
        $config->set('foo', 'somedata');
    })->throws(KeyAlreadyExistsException::class);
});
