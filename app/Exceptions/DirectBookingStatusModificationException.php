<?php

namespace App\Exceptions;

use RuntimeException;

class DirectBookingStatusModificationException extends RuntimeException
{
    // Custom exception to block direct updates on Booking status
}
