<?php

namespace Installer;

use Composer\Script\Event;

class ValidatorInstaller {
    public static function postInstall(Event $event) {
        $vendor_dir = $event->getComposer()->getConfig()->get('vendor-dir');
        $app_dir = dirname($vendor_dir) . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR;
        $i18n_dir = $app_dir . "i18n" . DIRECTORY_SEPARATOR . "en" . DIRECTORY_SEPARATOR;
        $target = $i18n_dir . "validator.php";

        copy('dist/validator.php', $target);
    }
}