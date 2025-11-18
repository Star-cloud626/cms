<?php
/**
 * PDF Layout
 *
 * @var \App\View\AppView $this
 */
header("Content-type: application/pdf");
echo $this->fetch('content');

