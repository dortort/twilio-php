<?php

namespace Twilio\Tests\Unit;

use Twilio\Security\RequestValidator;

class RequestValidatorTest extends UnitTest {
    /** @var RequestValidator */
    private $validator;
    private $params = [
        'CallSid' => 'CA1234567890ABCDE',
        'Caller' => '+14158675309',
        'Digits' => '1234',
        'From' => '+14158675309',
        'To' => '+18005551212',
    ];
    private $url = 'https://mycompany.com/myapp.php?foo=1&bar=2';
    private $signature = 'RSOYDt4T1cUTdK1PDd93/VVr8B8=';
    private $body = '{"property": "value", "boolean": true}';
    private $bodyHash = '0a1ff7634d9ab3b95db5c9a2dfe9416e41502b283a80c7cf19632632f96e6620';

    public function doSetUp() {
        $this->validator = new RequestValidator('12345');
    }

    public function testValidate() {
        $isValid = $this->validator->validate($this->signature, $this->url, $this->params);
        $this->assertTrue($isValid);
    }

    public function testFailsWhenIncorrect() {
        $isValid = $this->validator->validate("FAIL", $this->url, $this->params);
        $this->assertFalse($isValid);
    }

    public function testValidateBody() {
        $hash = $this->validator->computeBodyHash($this->body);
        $this->assertEquals($this->bodyHash, $hash);
    }

    public function testValidateWithBody() {
        $url = $this->url . '&bodySHA256=' . $this->bodyHash;
        $signatureWithHash = 'a9nBmqA0ju/hNViExpshrM61xv4=';

        $isValid = $this->validator->validate($signatureWithHash, $url, $this->body);
        $this->assertTrue($isValid);
    }

    public function testValidateBodyWithoutSignature() {
        $isValid = $this->validator->validate($this->signature, $this->url, $this->body);

        $this->assertFalse($isValid);
    }
}
