<?php namespace Ddedic\Numtector\Exceptions;


class RequiredFieldsException extends \UnexpectedValueException {}

class InvalidFieldFormatException extends \UnexpectedValueException {}
class InvalidFromFieldException extends InvalidFieldFormatException {}
class InvalidToFieldException extends InvalidFieldFormatException {}
class InvalidDestinationException extends InvalidFieldFormatException{}

class InvalidRequestException extends \RuntimeException {}

class GatewayException extends \RuntimeException {}
class InvalidGatewayProviderException extends GatewayException {}
class InvalidGatewayResponseException extends GatewayException {}


