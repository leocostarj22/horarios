<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

defined('_JEXEC') or die;

use Joomla\Component\Horarios\Site\Helper\MediaHelper;

/** @var \Joomla\Component\Horarios\Site\View\Municipios\HtmlView $this */

if (empty($this->banner) || empty($this->banner->image)) {
    return;
}

$img  = MediaHelper::url($this->banner->image);
$link = MediaHelper::safeUrl($this->banner->link_url);
?>
<div class="horarios-alertbar">
    <?php if ($link !== '') : ?>
        <a href="<?php echo htmlspecialchars($link, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
            <img src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>" alt="">
        </a>
    <?php else : ?>
        <img src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>" alt="">
    <?php endif; ?>
</div>
