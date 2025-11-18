<?php
/**
 * PDF View
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Poster $poster
 * @var array $settings
 */

// Constants units are in pt.
// The margin on the left side of the page.
define('PAGE_LEFT', $settings['pdf left margin'] ?? 25);

// The margin from the top of the page.
define('PAGE_TOP', $settings['pdf top margin'] ?? 65);

// The vertical space to leave for the title.
define('TITLE_HEIGHT', 14);

// The vertical space to leave for the images, if any.
define('FRONT_IMAGES_HEIGHT', 75);

// The margin at the bottom of the page.
define('PAGE_BOTTOM', $settings['pdf bottom margin'] ?? 30);

// The width to use for the page.
define('PAGE_WIDTH', $settings['pdf content width'] ?? 160);

/**
 * Check if image exists or copy it
 */
function check_copy($image_basename, $big = false) {
    if ($big) {
        $local_image = WWW_ROOT . 'img' . DS . 'detail' . DS . 'big' . DS . $image_basename;
        $remote_image = POSTER_IMAGE_DIR . 'big/' . $image_basename;
        if (!file_exists($local_image)) {
            @copy($remote_image, $local_image);
        }
    }

    $local_image = WWW_ROOT . 'img' . DS . 'detail' . DS . 'small' . DS . $image_basename;
    $remote_image = POSTER_IMAGE_DIR . 'small/' . $image_basename;
    if (!file_exists($local_image)) {
        @copy($remote_image, $local_image);
    }
}

/**
 * Make image tag with proper sizing
 */
function make_image_tag($image_basename, $maxWidth = 200, $maxHeight = 200, $big = false) {
    if (empty($image_basename)) {
        return '';
    }

    check_copy($image_basename, $big);
    $image = WWW_ROOT . 'img/detail/small/' . $image_basename;
    if ($big) {
        $image = WWW_ROOT . 'img/detail/big/' . $image_basename;
    }

    $dimensions = getimagesize($image);
    $width = $dimensions[0];
    $height = $dimensions[1];

    $resize = '';
    if ($width > $height) {
        $resize = 'width="' . $maxWidth . 'px"';
    } else {
        $resize = 'height="' . $maxHeight . 'px"';
    }

    return "<img src=\"$image\" $resize />";
}

/**
 * Get padding top for image
 */
function get_padding_top($image_basename) {
    if (empty($image_basename)) {
        return '0px';
    }

    $image = WWW_ROOT . 'img/detail/small/' . $image_basename;
    $dimensions = getimagesize($image);
    $width = $dimensions[0];
    $height = $dimensions[1];
    
    if ($height > $width) {
        return 0;
    }

    $space = ((200 - ((int)(($height / $width) * 200))) / 2);
    return $space . "px";
}

// Note: This requires the xtcpdf vendor library
// You may need to install it or use an alternative PDF library
// For now, this is a placeholder that shows the structure

// App::import('Vendor','xtcpdf');
// $pdf = new XTCPDF();
// ... rest of PDF generation code ...

// For now, output a message that PDF generation needs the vendor library
echo "PDF generation requires the xtcpdf vendor library. Please install it or configure an alternative PDF library.";

