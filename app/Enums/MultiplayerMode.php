<?php

namespace App\Enums;

abstract class MultiplayerMode extends Enum
{
    public const CAMPAIGN='Campaign Co-op';
    public const LAN='LAN Co-op';
    public const OFFLINE='Offline Co-op';
    public const ONLINE='Online Co-op';
    public const SPLITONLINE='Splitscreen Online Co-op';
    public const COUCH='Splitscreen Couch Co-op';
    public const DROPIN='Drop In/Out Multiplayer';
}