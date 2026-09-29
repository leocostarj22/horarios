<?php

/**
 * @package     Horarios e Circuitos
 * @subpackage  com_horarios
 * @author      Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 */

defined('_JEXEC') or die;

use Joomla\Component\Horarios\Site\Helper\MediaHelper;

/** @var \Joomla\Component\Horarios\Site\View\Municipios\HtmlView $this */

if (empty($this->reservas)) {
    return;
}
?>
<div class="horarios-reservas">
    <?php foreach ($this->reservas as $reserva) :
        if (empty($reserva->image)) {
            continue;
        }

        $img  = MediaHelper::url($reserva->image);
        $link = MediaHelper::safeUrl($reserva->link_url);
    ?>
        <div class="horarios-reservas-item">
            <?php if ($link !== '') : ?>
                <a href="<?php echo htmlspecialchars($link, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
                    <img src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo $this->escape($reserva->title); ?>">
                </a>
            <?php else : ?>
                <img src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo $this->escape($reserva->title); ?>">
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
