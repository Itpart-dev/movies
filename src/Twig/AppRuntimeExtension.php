<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppRuntimeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('video_url_2_embedUrl', [$this, 'videoUrl2EmbedUrl']),
            new TwigFilter('image_render', [$this, 'imageRender']),
        ];
    }

    /**
     * @param string $url
     * @return string
     */
    public function videoUrl2EmbedUrl(string $url): string
    {
        if (\preg_match('#youtube\.com/watch\?v=([^&]+)(?:.+t=(\d+))?#', $url, $matches)) {
            // Youtube URL
            $output = 'https://www.youtube.com/embed/'.$matches[1];
            if (\count($matches) > 2) {
                $output .= '?start='.$matches[2];
            }
        } elseif (\preg_match('#youtu\.be/([^?]+)(?:.+t=(\d+))?#', $url, $matches)) {
            // Youtube URL
            $output = 'https://www.youtube.com/embed/'.$matches[1];
            if (\count($matches) > 2) {
                $output .= '?start='.$matches[2];
            }
        } elseif (\preg_match('#dailymotion\.com/video/([^?]+)#', $url, $matches)) {
            // Dailymotion URL
            $output = 'https://www.dailymotion.com/embed/video/'.$matches[1];
        } elseif (\preg_match('#dai\.ly/([^?]+)#', $url, $matches)) {
            // Dailymotion URL
            $output = 'https://www.dailymotion.com/embed/video/'.$matches[1];
        } elseif (\preg_match('#vimeo.com/([^?]+)#', $url, $matches)) {
            $output = 'https://player.vimeo.com/video/'.$matches[1];
        } else {
            $output = $url;
        }

        return $output;
    }

    /**
     * @param string $part
     * @return string
     */
    public function imageRender(string $part)
    {
        return 'https://image.tmdb.org/t/p/original'.$part;
    }
}
