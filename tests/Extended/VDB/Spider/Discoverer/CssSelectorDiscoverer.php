<?php
namespace Tests\Extended\VDB\Spider\Discoverer;

use VDB\Spider\Discoverer\Discoverer;
use VDB\Spider\Resource;
use VDB\Spider\Spider;
use VDB\Uri\Exception\UriSyntaxException;
use VDB\Uri\Uri;
use VDB\Uri\UriInterface;

/**
 * @author Matthijs van den Bos
 * @copyright 2013 Matthijs van den Bos
 */
class CssSelectorDiscoverer implements Discoverer
{
    /** @var string */
    protected $cssSelector = 'a';

    /** @var string */
    protected $attribute = 'href';

    /**
     * @param $cssSelector
     */
    public function __construct($cssSelector = 'a', $attribute = 'href')
    {
        $this->cssSelector = $cssSelector;
        $this->attribute = $attribute;
    }

    /**
     * @param Spider $spider
     * @param Resource $document
     * @return UriInterface[]
     */
    public function discover(Spider $spider, Resource $document)
    {
        $crawler = $document->getCrawler()->filter($this->cssSelector);
        $uris = array();
        foreach ($crawler as $node) {
            try {
                $uris[] = new Uri($node->getAttribute($this->attribute), $document->getUri()->toString());
            } catch (UriSyntaxException $e) {
                $spider->getStatsHandler()->addToFailed(
                    $node->getAttribute($this->attribute),
                    'Invalid URI: ' . $e->getMessage()
                );
            }
        }
        return $uris;
    }
}
