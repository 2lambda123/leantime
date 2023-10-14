<?php

namespace Leantime\Domain\Plugins\Models {

    /**
     *
     */
    class Plugins
    {
        public ?int $id;
        public string $name;
        public bool $enabled;
        public string $description;
        public string $version;
        public string $imageUrl = '';
        public string $installdate;
        public string $foldername;
        public string $homepage;
        public string|array $authors;

        public ?string $format;

        public ?string $license;

        public ?string $type;

        /**
         * @return string
         */
        /**
         * @return string
         */
        public function getPluginImageData(): string
        {

            if(isset($this->imageUrl) && !empty($this->imageUrl) && $this->imageUrl != "false"){
                return $imageUrl;
            }

            $image = APP_ROOT . '/app/Plugins/' . str_replace(".", '', $this->foldername) . '/screenshot.png';
            if (file_exists($image)) {
                // Read image path, convert to base64 encoding
                $imageData = base64_encode(file_get_contents($image));
                return 'data: ' . mime_content_type($image) . ';base64,' . $imageData;
            } else {
                $image = APP_ROOT . "/public/dist/images/svg/undraw_search_app_oso2.svg";
                $imageData = base64_encode(file_get_contents($image));
                return 'data: ' . mime_content_type($image) . ';base64,' . $imageData;
            }
        }
    }
}
