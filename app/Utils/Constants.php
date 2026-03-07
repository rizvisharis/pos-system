<?php

namespace App\Utils;

class Constants
{
    public static $ERROR_MESSAGE = [
        'success' => 'Success!',
        'id_exist' => 'Id already exist!',
        'id_not_exist' => 'Id not already exist!',
        'access_denied' => 'You do not have access permission!',
        'insufficient_stock' => 'Insufficient stock for product',
        'order_already_cancelled' => 'Order already cancelled',
    ];

    public static $ERROR_CODE = [
        'success' => 200,
        'unauthorized' => 401,
        'access_denied' => 403,
        'not_found' => 404,
        'unprocessable_entity' => 422,
        'internal_server_error' => 500,
    ];

    public static $PAGINATION_PAGE_PARAM = 'api/';
}
