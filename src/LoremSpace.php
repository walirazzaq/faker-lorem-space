<?php

namespace Walirazzaq\FakerLoremSpace;

use Illuminate\Support\Facades\File;
use RuntimeException;

class LoremSpace
{
    private string $base_url = 'https://api.lorem.space/image';
    protected $categories = [
        'movie',
        'game',
        'album',
        'book',
        'face',
        'fashion',
        'shoes',
        'watch',
        'furniture',
    ];

    public function __construct(
        protected int $width = 640,
        protected int $height = 480,
        protected ?string $category = null,
    ) {
        $this->width($width)
            ->height($height)
            ->setValidCategory($category);
    }

    protected function limitToAllowedSize(int $size): int
    {
        if ($size >= 8 && $size <= 2000) {
            return $size;
        }
        if ($size < 8) {
            return 8;
        }
        if ($size > 2000) {
            return 2000;
        }
    }

    protected function setValidCategory(?string $category): self
    {
        if ($category && ! in_array($category, $this->categories)) {
            throw new RuntimeException(
                "Invalid lorem.space category:{$category}, possible are: [" . implode(',', $this->categories) . "]",
                1
            );
        }
        $this->category = $category;

        return $this;
    }

    public function url(): string
    {
        $url = implode("/", array_filter([
            $this->base_url,
            $this->category ?: null,
        ]));
        $query = http_build_query([
            'w' => $this->width,
            'h' => $this->height,
        ]);

        return implode("?", array_filter([$url, $query]));
    }

    public function save(?string $dir = null, string $extension = 'png', bool $fullPath = true): string
    {
        $dir = null === $dir ? sys_get_temp_dir() : $dir; // GNU/Linux / OS X / Windows compatible
        // Validate directory path
        if (! is_dir($dir) || ! is_writable($dir)) {
            throw new \InvalidArgumentException(sprintf('Cannot write to directory "%s"', $dir));
        }

        // Generate a random filename. Use the server address so that a file
        // generated at the same time on a different server won't have a collision.
        $name = md5(uniqid(empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR'], true));
        $filename = $name . '.' . $extension;
        $filepath = $dir . DIRECTORY_SEPARATOR . $filename;

        $url = $this->url();
        $context = stream_context_create(
            [
                'http' => [
                    'follow_location' => true,
                ],
            ]
        );

        $content = file_get_contents($url, false, $context);
        File::put($filepath, $content);

        return $fullPath ? $filepath : $filename;
    }

    public function __toString()
    {
        return $this->url();
    }

    public function height(int $height): self
    {
        $this->height = $this->limitToAllowedSize($height);

        return $this;
    }

    public function width(int $width): self
    {
        $this->width = $this->limitToAllowedSize($width);

        return $this;
    }

    public function category($category): self
    {
        $this->setValidCategory($category);

        return $this;
    }
}
