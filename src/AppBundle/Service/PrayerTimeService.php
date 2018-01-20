<?php

namespace AppBundle\Service;

use AppBundle\Entity\Mosque;

class PrayerTimeService {

    private $cacheDir;

    public function __construct($cacheDir) {
        $this->cacheDir = $cacheDir;
    }

    /**
     * true if mosque or configuration has been updated
     * @param Mosque $mosque
     * @param string $lastUpdatedDate
     * @return string
     */
    function mosqueHasBeenUpdated(Mosque $mosque, $lastUpdatedDate) {
        return $mosque->getUpdated() > $lastUpdatedDate;
    }

    /**
     * transforme json calendar in csv files and compress theme in a zip file
     * @param Mosque $mosque
     * @return string the path of the zip file
     */
    function getFilesFromCalendar(Mosque $mosque) {

        $calendar = $mosque->getConfiguration()->getCalendar();
        if (is_array($calendar)) {
            $path = $this->cacheDir."/" . $mosque->getId();
            if (!is_dir($path)) {
                mkdir($path);
            }
            foreach ($calendar as $key => $month) {
                $str = "Day,Fajr,Shuruk,Duhr,Asr,Maghrib,Isha\n";
                foreach ($month as $day => $prayers) {
                    $str .= "$day," . implode(",", $prayers) . "\n";
                }
                $fileName = str_pad($key + 1, 2, "0", STR_PAD_LEFT) . ".csv";

                file_put_contents("$path/$fileName", $str);
            }

            $zip = new \ZipArchive();
            $zipFileName = $mosque->getSlug() . ".zip";
            $zipFilePath = "$path/" . $zipFileName;

            if ($zip->open($zipFilePath, \ZipArchive::CREATE) === true) {
                $zip->addGlob("$path/*.csv", GLOB_BRACE, array('remove_all_path' => TRUE));
                $zip->close();
            }

            return $zipFilePath;
        }
        return null;
    }

}
