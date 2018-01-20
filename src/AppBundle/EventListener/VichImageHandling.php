<?php

namespace AppBundle\EventListener;

use Vich\UploaderBundle\Event\Event;
use AppBundle\Entity\Mosque;

class VichImageHandling {

    const IMAGE_WIDTH = 1280;
    const IMAGE_HEIGHT = 840;


    public function resizeImage(Event $event) {
        $object = $event->getObject();
        if ($object instanceof Mosque) {
            $destinationDir = $event->getMapping()->getUploadDestination();
            $fileName = $event->getMapping()->getFileName($object);
            $filePath = $destinationDir . '/' . $fileName;
            if(file_exists($filePath)) {
                $image = new \Imagick($filePath);
                $image->scaleimage(self::IMAGE_WIDTH, self::IMAGE_HEIGHT);
                $image->writeimage();
            }
        }
    }
}
