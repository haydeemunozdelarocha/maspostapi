<?php

namespace MaspostAPI\Repositories;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use PHPZxing\PHPZxingDecoder;
use PHPZxing\ZxingImage;

class Captura
{
    public static function readBarcodes($path)
    {
        $config = array(
            'try_harder'            => true,
        );
        $realJavaPath = exec('greadlink -f /usr/bin/java');
        $decoder        = new PHPZxingDecoder($config);
        $decoder->setJavaPath($realJavaPath);
        $data           = $decoder->decode('/Users/haydeemunoz/PhpstormProjects/maspostwarehouseusers/api/Code128Barcode.jpg');
        if($data instanceof ZxingImage) {
            return $data;
        } else {

            return 'ERROR';
        }
    }
public static function readLabel($path) {

//    $imageAnnotator = new ImageAnnotatorClient([
//        'keyFilePath' => '/Users/haydeemunoz/PhpstormProjects/maspostwarehouseusers/api/credentials.json',
//        'projectId' => 'maspost',
//        ]);
//    # annotate the image
//    $image = file_get_contents($path);
//    $response = $imageAnnotator->textDetection($image);
//    $annotation = $response->getFullTextAnnotation();
    $resblocks = [];
//    if ($annotation) {
//        foreach ($annotation->getPages() as $page) {
//            foreach ($page->getBlocks() as $block) {
//                $block_text = '';
//                foreach ($block->getParagraphs() as $paragraph) {
//                    foreach ($paragraph->getWords() as $word) {
//                        foreach ($word->getSymbols() as $symbol) {
//                            $block_text .= $symbol->getText();
//                        }
//                        $block_text .= ' ';
//                    }
//                    $block_text .= "\n";
//                }
//                printf('Block content: %s', $block_text);
//                printf('Block confidence: %f' . PHP_EOL,
//                    $block->getConfidence());
//
//                array_push($blocks, $block);
//                # get bounds
//                $vertices = $block->getBoundingBox()->getVertices();
//                $bounds = [];
//                foreach ($vertices as $vertex) {
//                    $bounds[] = sprintf('(%d,%d)', $vertex->getX(),
//                        $vertex->getY());
//                }
//                print('Bounds: ' . join(', ', $bounds) . PHP_EOL);
//                print(PHP_EOL);
//            }
//        }
//    } else {
//        print('No text found' . PHP_EOL);
//    }

//    $imageAnnotator->close();
    //return json_decode($annotation->serializeToJsonString());
    return '';
}
}
