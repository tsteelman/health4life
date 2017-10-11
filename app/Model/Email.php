<?php

App::uses('AppModel', 'Model');

/**
 * Email Model
 *
 */
class Email extends AppModel {

    const STATUS_NOT_SEND = 0;
    const STATUS_SEND = 1;
    const DEFAULT_SEND_PRIORITY = 1;
    const HIGH_PRIORITY = 2;
}
