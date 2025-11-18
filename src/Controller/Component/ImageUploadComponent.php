<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * ImageUpload component
 */
class ImageUploadComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [];

    /**
     * Uploads an image and its thumbnail
     *
     * @param array $filedata File data from $_FILES
     * @param int $imgscale Maximum width or height for main image
     * @param int $thumbscale Maximum width or height for thumbnail
     * @param string $folderName Folder name to store images
     * @param bool $square Whether to create square cropped thumbnails
     * @param string|null $watermark Watermark filename
     * @return string|null Filename of uploaded image
     * @throws \Exception
     */
    public function uploadImageAndThumbnail(array $filedata, int $imgscale, int $thumbscale, string $folderName, bool $square, ?string $watermark = null): ?string
    {
        if (strlen($filedata['name'] ?? '') <= 4) {
            return null;
        }

        $filetype = $this->getFileExtension($filedata['name']);
        $filetype = strtolower($filetype);

        if (!in_array($filetype, ['jpeg', 'jpg', 'gif', 'png'])) {
            throw new \Exception('File extension is not supported (required jpeg, jpg, gif, or png)');
        }

        // Get the image size
        $imgsize = getimagesize($filedata['tmp_name']);
        if ($imgsize === false) {
            throw new \Exception('Invalid image file');
        }

        // Generate a unique name for the image
        $id_unic = str_replace(".", "", (string)microtime(true));
        $filename = $id_unic . "." . $filetype;

        $img_root = WWW_ROOT . 'img' . DS;
        $biguploaddir = $img_root . $folderName . DS . "big";
        $smalluploaddir = $img_root . $folderName . DS . "small";

        $bigfile = $biguploaddir . DS . $filename;
        $croppedfile = $smalluploaddir . DS . $filename;

        // Make sure directories exist
        if (!is_dir($biguploaddir)) {
            mkdir($biguploaddir, 0777, true);
        }
        if (!is_dir($smalluploaddir)) {
            mkdir($smalluploaddir, 0777, true);
        }

        if (is_uploaded_file($filedata['tmp_name'])) {
            if (!move_uploaded_file($filedata['tmp_name'], $bigfile)) {
                throw new \Exception('Could not write uploaded file.');
            }

            if ($watermark) {
                $this->applyWatermark($bigfile, $watermark);
            }

            if ($square) {
                $this->cropImg($bigfile, $thumbscale, $croppedfile);
            } else {
                $this->resizeImg($bigfile, $thumbscale, $croppedfile);
            }
        }

        return $filename;
    }

    /**
     * Apply watermark to image
     */
    public function applyWatermark(string $imgname, string $watermark): void
    {
        $watermarkPath = WWW_ROOT . 'img' . DS . 'watermark' . DS . $watermark;
        if (!file_exists($watermarkPath)) {
            return;
        }

        $wm_src = $this->createImageFromFile($watermarkPath);
        $wm_width = imagesx($wm_src);
        $wm_height = imagesy($wm_src);

        $img_src = $this->createImageFromFile($imgname);
        $img_width = imagesx($img_src);
        $img_height = imagesy($img_src);
        imagealphablending($img_src, false);
        imagesavealpha($img_src, true);

        // Scale to percent of original image
        $max_ratio = 0.30;
        $adj_wm_height = $wm_height;
        $adj_wm_width = $wm_width;
        if ($adj_wm_width > $adj_wm_height) {
            $adj_wm_width = (int)($img_width * $max_ratio);
            $adj_wm_height = (int)($adj_wm_height * ($adj_wm_width / $wm_width));
        } else {
            $adj_wm_height = (int)($img_height * $max_ratio);
            $adj_wm_width = (int)($adj_wm_width * ($adj_wm_height / $wm_height));
        }

        $adj_wm_src = imagecreatetruecolor($adj_wm_width, $adj_wm_height);
        imagealphablending($adj_wm_src, false);
        imagesavealpha($adj_wm_src, true);
        imagecopyresampled($adj_wm_src, $wm_src, 0, 0, 0, 0, $adj_wm_width, $adj_wm_height, $wm_width, $wm_height);
        imagedestroy($wm_src);
        $wm_src = $adj_wm_src;
        $wm_height = $adj_wm_height;
        $wm_width = $adj_wm_width;

        // Center position
        $wm_position_x = (int)abs(($img_width / 2) - ($wm_width / 2));
        $wm_position_y = (int)abs(($img_height / 2) - ($wm_height / 2));

        $this->imagecopymergeAlpha($img_src, $wm_src, $wm_position_x, $wm_position_y, 0, 0, $wm_width, $wm_height, 1);

        $this->saveImageToFile($img_src, $imgname);

        imagedestroy($img_src);
        imagedestroy($wm_src);
    }

    /**
     * Image copy merge with alpha
     */
    protected function imagecopymergeAlpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct): void
    {
        $opacity = 100 - ($pct * 100);
        $cut = imagecreatetruecolor($src_w, $src_h);
        imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
        imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
        imagecopymerge($dst_im, $cut, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity);
        imagedestroy($cut);
    }

    /**
     * Delete image and thumbnail
     */
    public function deleteImage(string $filename, string $folderName): void
    {
        $img_root = WWW_ROOT . 'img' . DS;
        @unlink($img_root . $folderName . "/big/" . $filename);
        @unlink($img_root . $folderName . "/small/" . $filename);
    }

    /**
     * Crop image to square
     */
    public function cropImg(string $imgname, int $scale, string $filename): void
    {
        $img_src = $this->createImageFromFile($imgname);

        $width = imagesx($img_src);
        $height = imagesy($img_src);
        $ratiox = ($width / $height) * $scale;
        $ratioy = ($height / $width) * $scale;

        $newheight = ($width <= $height) ? $ratioy : $scale;
        $newwidth = ($width <= $height) ? $scale : $ratiox;

        $cropx = ($newwidth - $scale != 0) ? ($newwidth - $scale) / 2 : 0;
        $cropy = ($newheight - $scale != 0) ? ($newheight - $scale) / 2 : 0;

        $resampled = imagecreatetruecolor((int)$newwidth, (int)$newheight);
        $cropped = imagecreatetruecolor($scale, $scale);

        imagecopyresampled($resampled, $img_src, 0, 0, 0, 0, (int)$newwidth, (int)$newheight, $width, $height);
        imagecopy($cropped, $resampled, 0, 0, (int)$cropx, (int)$cropy, (int)$newwidth, (int)$newheight);

        $this->saveImageToFile($cropped, $filename);

        imagedestroy($img_src);
        imagedestroy($resampled);
        imagedestroy($cropped);
    }

    /**
     * Resize image maintaining aspect ratio
     */
    public function resizeImg(string $imgname, int $size, string $filename): void
    {
        $img_src = $this->createImageFromFile($imgname);

        $true_width = imagesx($img_src);
        $true_height = imagesy($img_src);

        if ($true_width >= $true_height) {
            $width = $size;
            $height = (int)(($width / $true_width) * $true_height);
        } else {
            $height = $size;
            $width = (int)(($height / $true_height) * $true_width);
        }

        $img_des = imagecreatetruecolor($width, $height);
        imagecopyresampled($img_des, $img_src, 0, 0, 0, 0, $width, $height, $true_width, $true_height);

        $this->saveImageToFile($img_des, $filename);

        imagedestroy($img_src);
        imagedestroy($img_des);
    }

    /**
     * Get file extension
     */
    public function getFileExtension(string $str): string
    {
        $i = strrpos($str, ".");
        if ($i === false) {
            return "";
        }
        return substr($str, $i + 1);
    }

    /**
     * Create image from file
     */
    protected function createImageFromFile(string $filename)
    {
        $filetype = strtolower($this->getFileExtension($filename));

        switch ($filetype) {
            case "jpeg":
            case "jpg":
                return imagecreatefromjpeg($filename);
            case "gif":
                return imagecreatefromgif($filename);
            case "png":
                return imagecreatefrompng($filename);
            default:
                throw new \Exception("Unsupported image type: $filetype");
        }
    }

    /**
     * Save image to file
     */
    protected function saveImageToFile($image, string $filename, int $quality = 80): void
    {
        $filetype = strtolower($this->getFileExtension($filename));

        switch ($filetype) {
            case "jpeg":
            case "jpg":
                imagejpeg($image, $filename, $quality);
                break;
            case "gif":
                imagegif($image, $filename);
                break;
            case "png":
                imagepng($image, $filename, (int)($quality / 10));
                break;
        }
    }

    /**
     * Get available watermarks
     */
    public function getAvailableWatermarks(): array
    {
        $retval = ['' => "None"];
        $supportedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $watermarkPath = WWW_ROOT . 'img' . DS . 'watermark' . DS;

        if (is_dir($watermarkPath) && $handle = opendir($watermarkPath)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $ext = strtolower($this->getFileExtension($file));
                    if (in_array($ext, $supportedFileTypes)) {
                        $retval[$file] = $file;
                    }
                }
            }
            closedir($handle);
        }

        return $retval;
    }
}

