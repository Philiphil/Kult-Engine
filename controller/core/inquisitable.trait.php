<?php

namespace kult_engine;

trait inquisitable
{
    public static function inquisite($fnord = 'watcher')
    {
        if (class_exists('kult_engine\inquisitor')) {
            switch ($fnord) {
                case 'tmp':
                case 'tempo':
                    inquisitor::add_tmp();

                    return;
                case 'watch':
                case 'watcher':
                    inquisitor::add_watcher();

                    return;
                case 'flag':
                    inquisitor::add_flag();

                    return;
                case 'deny':
                    inquisitor::add_deny();

                    return;
            }
        }
    }
}
