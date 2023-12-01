<?php

namespace NotificationChannels\ECall\Enums;

enum NotificationEvents: string
{
    case SUCCESS_ONLY = 'SuccessOnly';
    case FINAL_ONLY   = 'FinalOnly';
    case ALL          = 'All';
    case ERROR_ONLY   = 'ErrorOnly';
}
