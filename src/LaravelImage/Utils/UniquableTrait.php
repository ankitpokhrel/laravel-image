<?php

namespace AnkitPokhrel\LaravelImage\Utils;

trait UniquableTrait
{
    /**
     * function to generate unique filename for images.
     *
     * @param string $filename
     *
     * @return string
     */
    public function getUniqueFilename($filename)
    {
        $uniqueName = uniqid();
        $fileExt    = explode('.', $filename);
        $mimeType   = end($fileExt);
        $filename   = $uniqueName . '.' . $mimeType;

        return $filename;
    }

    /**
     * Generate a random UUID for folder name (version 4).
     *
     * @see       http://www.ietf.org/rfc/rfc4122.txt
     *
     * @return string RFC 4122 UUID
     *
     * @copyright Matt Farina MIT License https://github.com/lootils/uuid/blob/master/LICENSE
     */
    public function getUniqueFolderName()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 4095) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535)
        );
    }
}
