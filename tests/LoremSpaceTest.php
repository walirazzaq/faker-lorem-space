<?php

use Walirazzaq\FakerLoremSpace\LoremSpace;

it('can return url', function () {
    $expected = 'https://api.lorem.space/image?w=500&h=500';
    $actual = (new LoremSpace(500, 500))->url();

    expect($expected)->toBe($actual);
});

it('can limit the lower bound size', function () {
    $expected = 'https://api.lorem.space/image?w=8&h=8';
    $actual = (new LoremSpace(0, 0))->url();
    expect($expected)->toBe($actual);
});

it('can limit the upper bound size', function () {
    $expected = 'https://api.lorem.space/image?w=2000&h=2000';
    $actual = (new LoremSpace(2001, 2001))->url();
    expect($expected)->toBe($actual);
});

it('validates category', function () {
    (new LoremSpace(500, 500))->category("this-doesnt-exist")->url();
})->throws(RuntimeException::class);

it('can download & save file', function () {
    $path = (new LoremSpace(8, 8))->save();
    expect(file_exists($path))->toBeTrue();
    unlink($path);
    expect(file_exists($path))->toBeFalse();
});
