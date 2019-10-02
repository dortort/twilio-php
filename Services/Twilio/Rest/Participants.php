<?php

class Services_Twilio_Rest_Participants
    extends Services_Twilio_ListResource
{
    public function create($from, $to, array $params = array())
    {

        $params["To"] = $to;
        $params["From"] = $from;

        return parent::_create($params);
    }

    /* Participants are identified by CallSid, not like PI123 */
    public function getObjectFromJson($params, $idParam = "sid") {
        return parent::getObjectFromJson($params, "call_sid");
    }
}
