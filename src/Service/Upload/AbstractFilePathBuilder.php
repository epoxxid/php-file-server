<?php declare(strict_types=1);

namespace App\Service\Upload\File;

abstract class AbstractFilePathBuilder
{
    /**
     * Generate unique identifier for file with specified extension
     * @param string $extension
     * @return string
     */
    public function generateFileIdentifier(string $extension): string
    {
        // generate random unique string
        $identifier = md5(uniqid('', true));
        return sprintf(
            '%s/%s.%s',
            substr($identifier, 0, 2),
            substr($identifier, 2),
            $extension
        );
    }

    /**
     * Add prefix and return identifier
     * @param string $prefix
     * @param string $identifier
     * @return string
     */
    protected function addPrefix(string $prefix, string $identifier): string
    {
        return sprintf('%s/%s', $prefix, $identifier);
    }
}
